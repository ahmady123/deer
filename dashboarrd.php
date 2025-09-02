<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="mammal.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manage Articles, Comments, Replies</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7f9;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #4A90E2;
}

button {
    background-color: #4A90E2;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #357ABD;
}

input[type="text"] {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 20px;
}

.list {
    margin-top: 20px;
}

.list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    margin: 5px 0;
    transition: background-color 0.3s ease;
}

.list-item:hover {
    background-color: #f0f0f0;
}

#confirmPopup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}

#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

#confirmPopup p {
    margin: 0 0 15px;
    text-align: center;
}

#confirmDelete, #cancelDelete {
    width: 45%;
    margin: 0 2.5%;
}

#confirmDelete {
    background-color: #d9534f;
}

#confirmDelete:hover {
    background-color: #c9302c;
}

#cancelDelete {
    background-color: #5bc0de;
}

#cancelDelete:hover {
    background-color: #31b0d5;
}
#passwordPopupOverlay {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: ;
            background-color: #f8f8f8; 
            z-index: 999;
        }
        #passwordPopup {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        #passwordPopup p {
            margin: 0 0 15px;
            text-align: center;
        }

        #submitPassword {
            background-color: #4A90E2;
        }

        #submitPassword:hover {
            background-color: #357ABD;
        }
    </style>
</head>
<style>

#mainContent {
    visibility: hidden;
}

#passwordPopupOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}


#passwordPopup {
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 300px;
    text-align: center;
}

    </style>
<body>

    
    <div id="passwordPopupOverlay">
        <div id="passwordPopup">
            <p>Please enter your email and password to access the dashboard:</p>
            <input type="email" id="emailInput" placeholder="Enter email">
            <input type="password" id="passwordInput" placeholder="Enter password"><br><br>
            <button id="submitPassword">Submit</button>
        </div>
    </div>

    <div id="mainContent">
        <div class="container">
            <h1>Manage Articles, Comments, and Replies</h1>
            <button onclick="showSection('articles')">Articles</button>
            <button onclick="showSection('comments')">Comments</button>
            <button onclick="showSection('replies')">Replies</button>

            <input type="text" id="searchBar" placeholder="Search..." onkeyup="searchItems()">

            <div id="articles" class="list" style="display:none;">
                <h2>Articles</h2>
                <div id="articlesList"></div>
            </div>

            <div id="comments" class="list" style="display:none;">
                <h2>Comments</h2>
                <div id="commentsList"></div>
            </div>

            <div id="replies" class="list" style="display:none;">
                <h2>Replies</h2>
                <div id="repliesList"></div>
            </div>
        </div>
    </div>

    <div id="overlay"></div>
    <div id="confirmPopup">
        <p>Are you sure you want to delete this item?</p>
        <button id="confirmDelete">Yes</button>
        <button id="cancelDelete">No</button>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
