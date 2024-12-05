<div class="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <h2>Admin</h2>
        </div>
        <div class="border"></div>
        <br>
        <!-- Navigation Links -->
        <div class="sidebar-section">
            <a href="#" class="sidebar-link active">
                <i class="icon">🏠</i> Overview
            </a>
            <a href="#" class="sidebar-link">
                <i class="icon">🔥</i> Manage Post 
            </a>
            <a href="#" class="sidebar-link">
                <i class="icon">🔥</i> Manage User
            </a>
            <a href="#" class="sidebar-link">
                <i class="icon">🔥</i> Settings 
            </a>
            <a href="#" class="sidebar-link">
                <i class="icon">🔥</i> Logout
            </a>
        </div>  
    <!-- Footer -->

    <footer>
    <div class="footer-content">
        <p>&copy; 2024 Blogging System. All rights reserved.</p>
        </div>
    </footer>
    </body>
    </html>
    </div>


    <style>

        /* Footer */
footer {
    background-color: #121212; /* Background color */
    padding: 15px;
    text-align: center;
    color: white;
    position: absolute; /* Ensure it's placed correctly */
    width: 100%;
    bottom: 0;
    border-top: 1px solid #999999; /* Outline above the footer */
}

footer .footer-content {
    font-size: 14px;
}

    .border{
        border-top: 1px solid #999999;
    }


/* Sidebar */
.sidebar {
    background-color: #121212; /* Distinct dark blue-gray background for the sidebar */
    height: 101%; /* Ensure it fits below the navbar */
    overflow-y: auto;
    overflow-x: hidden; /* Prevent horizontal scrolling */
    box-sizing: border-box;
    position: fixed;
     box-shadow: 3px 0 5px rgba(0, 0, 0, 0.3); /*Horizontal shadow on the right */
    margin-left: -15px;
    margin-top: -15px; /* Align with the height of the navbar */
    width: 250px; /* Set a specific width for the sidebar */
    color: #ffffff; /* Text color for contrast */
    animation: slideIn 0.5s ease-out; /* Applying slideIn animation */
}

/* Sidebar Logo */
.sidebar-logo h2 {
    color: #00bfff;
    margin-top: 30px;
    margin-bottom: 20px;
    padding-left: 50px;
    font-size: 1.5rem;
    font-weight: bold;
    animation: fadeIn 0.6s ease-in-out; /* Fade in animation for the logo */
}

/* Sidebar sections */
.sidebar-section {
    padding-left: 30px;
    padding-right: 30px;
    margin-bottom: 30px;
    animation: fadeInUp 0.7s ease-in-out; /* Fade in and move up animation for each section */
}

.sidebar-section h3 {
    font-size: 1rem;
    color: #ccc;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-weight: 600;
}

/* Sidebar links */
.sidebar-link,
.sidebar-section ul li a {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 10px;
    border-radius: 8px;
    transition: background-color 0.2s;
    font-size: 1rem;
    animation: fadeInUp 0.8s ease-in-out; /* Fade in and move up animation for links */
}

.sidebar-link .icon,
.sidebar-section ul li a .icon {
    margin-right: 10px;
    font-size: 1.2rem;
}

.sidebar-link.active,
.sidebar-link:hover,
.sidebar-section ul li a:hover {
    background-color: #333333;
}

/* Sidebar list items */
.sidebar-section ul {
    list-style: none;
    padding: 0;
}

.sidebar-section ul li {
    margin: 5px 0;
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

/* Animations */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    0% { transform: translateX(-30px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes slideInLeft {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(0); }
}

@keyframes slideInUp {
    0% { transform: translateY(30px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}
</style>