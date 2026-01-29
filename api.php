<?php
header("Content-Type: application/json");
require 'db_connect.php';

$action = $_GET['action'] ?? '';

// 1. Get List of Countries
if ($action == 'get_countries') {
    $sql = "SELECT * FROM countries";
    $result = $conn->query($sql);
    $countries = [];
    while($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    echo json_encode($countries);
    exit;
}

// 2. Get Cities based on Country ID
if ($action == 'get_cities') {
    $country_id = $_GET['country_id'] ?? 0;
    $sql = "SELECT * FROM cities WHERE country_id = $country_id";
    $result = $conn->query($sql);
    $cities = [];
    while($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
    echo json_encode($cities);
    exit;
}

// 3. Calculate Route (The Dijkstra Logic)
if ($action == 'calculate_route') {
    $source_id = $_GET['source_id'];
    $dest_id = $_GET['dest_id'];
    $criteria = $_GET['criteria']; // 'distance' or 'cost'

    // Fetch all nodes (cities) and edges (routes)
    // In a massive app, we would only fetch relevant country data, 
    // but for 100 cities, fetching all is very fast.
    
    $cities = [];
    $cityResult = $conn->query("SELECT id, name FROM cities");
    while($r = $cityResult->fetch_assoc()) {
        $cities[$r['id']] = $r['name'];
    }

    $adjList = [];
    $routeResult = $conn->query("SELECT * FROM routes");
    while($r = $routeResult->fetch_assoc()) {
        $u = $r['source_city_id'];
        $v = $r['dest_city_id'];
        $weight = ($criteria == 'cost') ? $r['cost'] : $r['distance'];

        // Undirected Graph (Two-way streets)
        $adjList[$u][] = ['node' => $v, 'weight' => $weight];
        $adjList[$v][] = ['node' => $u, 'weight' => $weight];
    }

    // --- DIJKSTRA'S ALGORITHM IMPLEMENTATION ---
    $dist = [];
    $parent = [];
    $pq = new SplPriorityQueue();

    // Initialize distances to Infinity
    foreach ($cities as $id => $name) {
        $dist[$id] = INF;
        $parent[$id] = null;
    }

    $dist[$source_id] = 0;
    // Insert into queue: [distance, city_id]
    // Note: SplPriorityQueue is a MaxHeap, so we negate distance to make it a MinHeap
    $pq->insert([0, $source_id], 0);

    while (!$pq->isEmpty()) {
        $current = $pq->extract();
        $currentDist = -$current[0]; // Negate back to positive
        $u = $current[1];

        if ($currentDist > $dist[$u]) continue;
        if ($u == $dest_id) break; // Reached destination

        if (isset($adjList[$u])) {
            foreach ($adjList[$u] as $edge) {
                $v = $edge['node'];
                $weight = $edge['weight'];

                if ($dist[$u] + $weight < $dist[$v]) {
                    $dist[$v] = $dist[$u] + $weight;
                    $parent[$v] = $u;
                    $pq->insert([-$dist[$v], $v], -$dist[$v]);
                }
            }
        }
    }

    // Construct Path
    if ($dist[$dest_id] == INF) {
        echo json_encode(['error' => 'No path found']);
    } else {
        $path = [];
        $curr = $dest_id;
        while ($curr != null) {
            $path[] = $cities[$curr]; // Store City Name
            $curr = $parent[$curr];
        }
        $path = array_reverse($path);

        echo json_encode([
            'path' => $path,
            'total_value' => $dist[$dest_id],
            'unit' => ($criteria == 'cost') ? 'PKR' : 'km'
        ]);
    }
    exit;
}

$conn->close();
?>
