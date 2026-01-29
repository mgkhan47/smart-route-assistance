# 🌍📍 Smart Route Assistance

Smart Route Assistance is a web-based optimal pathfinding system developed as a semester project for the Data Structures course at Air University.  
The application calculates the most efficient travel routes between cities using Dijkstra’s Algorithm, allowing users to find paths based on either Shortest Distance or Lowest Cost.

Note: The route data and costs used in this application are for demonstration and academic purposes only.

---

## 🚀 Features

- Optimal Pathfinding: Implements Dijkstra’s Algorithm in PHP to calculate routes in real-time
- Dual Criteria: Users can choose between Shortest Distance (km) or Lowest Cost (currency)
- Dynamic Database: Relational MySQL database storing Countries, Cities, and Route weights
- Interactive UI: Vintage-themed, responsive interface with dynamic dropdowns based on country selection
- Visual Route Rendering: Displays the calculated path as a sequence of nodes and arrows

---

## 🛠️ Tech Stack

- Frontend: HTML5, CSS3 (Custom "Vintage Explorer" Theme), JavaScript (Fetch API)
- Backend: PHP (REST-style API logic)
- Database: MySQL (Relational Schema)
- Server: XAMPP (Apache HTTP Server)
- Algorithm: Dijkstra’s Algorithm (Priority Queue Implementation)

---

## 📂 Project Structure
```bash
/route_app
├── api.php           # Backend logic (Dijkstra's Algorithm & Data Fetching)
├── db_connect.php    # Database connection configuration
├── index.html        # Main user interface
├── script.js         # Frontend logic (AJAX requests & DOM manipulation)
├── style.css         # Custom styling (Vintage Map Theme)
└── qwerty1.jpg       # Background image asset
```

---

## ⚙️ Installation & Setup

1. Install XAMPP  
   Download and install XAMPP for your operating system.

2. Clone the Repository  
   git clone https://github.com/mgkhan47/smart-route-assistance.git

3. Setup the Database  
   - Open phpMyAdmin (http://localhost/phpmyadmin)  
   - Create a new database named smart_route_db  
   - Import the provided SQL script or create tables manually  

4. Deploy the Project  
   - Copy the project folder into the htdocs directory  
     Example:  
     C:\xampp\htdocs\route_app  

5. Run the Application  
   - Start Apache and MySQL from XAMPP Control Panel  
   - Open your browser and navigate to:  
     http://localhost/route_app  

---

## 🧠 How It Works

1. Select Country: User selects a country (e.g., Pakistan, India, Turkey)
2. Select Cities: Source and Destination dropdowns populate dynamically
3. Choose Criteria: Select Shortest Distance or Lowest Cost
4. Find Route: PHP backend builds a weighted graph and runs Dijkstra’s Algorithm
5. View Results: Optimal route is displayed with total distance or cost

---

## 🎓 Learning Outcomes

- Graphs and weighted edges
- Dijkstra’s shortest path algorithm
- Priority queue implementation
- Relational database design
- Client–server architecture
- Dynamic UI updates using JavaScript
- Real-world application of Data Structures

---

## 📝 Author

Muhammad Gulraiz Khan  
Department of Cyber Security  
Air University
