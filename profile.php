<?php
session_start();
include("connect.php");
$status = isset($_GET['status']) ? $_GET['status'] : '';
$email = $_SESSION['email'] ?? null; 

if (!$email) {
    die("Session email not found. Please log in."); 
}

$query = "SELECT profile_picture, name FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($profilePicture, $userName);
$stmt->fetch();
$stmt->close();
$isProfileUpdated = !empty($profilePicture) && !empty($userName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="mammal.png">
    <link rel="stylesheet" href="profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
.nav-link, .button, .input-field{
    color: #000 !important;
    font-weight: bold;
}
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999999;
}

.popup-content {
  background-color: white;
  border: 1px solid #ccc;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  width: 300px;
}
.article-element {
    display: flex; 
    justify-content: space-between;
    align-items: center; 
}

.article-element .btn-danger {
    margin-left: auto;
}

.j {
    display: inline-block;
    padding: 0.5rem 1rem; 
    font-size: 14px; 
    font-weight: 700; 
    color: black; 
    border: 3px solid rgb(252, 70, 100); 
    cursor: pointer;
    position: relative; 
    background-color: #d0afde; 
    text-decoration: none;
    overflow: hidden; 
    z-index: 1; 
    font-family: inherit; 
    margin: 5px;
}

.j::before {
    content: "";
    position: absolute;
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgb(252, 70, 100); 
    transform: translateX(-100%); 
    transition: all .3s;
    z-index: -1;
}

.j:hover::before {
    transform: translateX(0); 
}
.favorites-container {
    display: flex; 
    align-items: center; 
}

.favorites-container a {
    font-weight: bold;
    color: black; 
    text-decoration: none; 
    transition: color 0.3s; 
}

.favorites-container a:hover {
    color:blue !important; 
}
#removeFavoritePopup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: none;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    z-index: 99999999;
}
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="mammal.png" alt="Logo" style="max-height: 40px;"> Deer
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>          
                    
               
                </ul>
                <button class="button border-0" id="button2" 
    style="border: none; background-color: transparent; padding: 0;" 
    onclick="showLogoutConfirmPopup()"> 
    <i class="fa-solid fa-right-from-bracket"></i>Logout 
</button>
<div id="logoutConfirmPopup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <p>Are you sure you want to log out?</p>
        <button id="confirmLogout" class="btn btn-danger">Confirm</button>
        <button id="cancelLogout" class="btn btn-secondary">Cancel</button>
    </div>
</div>
            </div>
        </div>
    </nav>
    <div class="container-fluid asd">
  
    <div class="cover-image-container">
        <img src="pexels-fauxels-3184460.jpg" alt="Cover Image" class="cover-image">
    </div>
   

    <form id="profile-picture-form" enctype="multipart/form-data">
        <div class="profile-picture-container text-center">
            <img id="profile-picture" src="<?php echo $profilePicture ? $profilePicture : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOuxrvcNMfGLh73uKP1QqYpKoCB0JLXiBMvA&s'; ?>" alt="Profile Picture" style="max-height: 150px;">
        </div>

        <?php if (!$isProfileUpdated): ?>
            <input type="text" id="user-name" name="user_name" placeholder="Enter your name" style="margin-top: 80px;" value="<?php echo htmlspecialchars($userName); ?>" required>
            <h5 id="display-name"  style="margin-top: 80px;"><?php echo htmlspecialchars($userName); ?></h5>
            <input type="file" id="profile-picture-upload" name="profile_picture_upload" accept="image/*" required>
            <button type="submit" class="btn btn-primary mt-3">Upload name and image</button>
        <?php else: ?>
            <h1 id="display-name" style="margin-top: 80px;"><?php echo htmlspecialchars($userName); ?></h1>
            <button type="button" class="btn btn-primary mt-3" id="edit-name-btn">Edit Name</button>
            <button type="button" class="btn btn-primary mt-3" id="edit-pic-btn">Edit Picture</button>

            <div id="name-input-container" style="display: none;">
                <input type="text" id="new-name" placeholder="Enter your new name" required>
                <button type="button" class="btn btn-success mt-3" id="save-name-btn">Save</button>
            </div>
        <?php endif; ?>
    </form>
</div>
<br><br>
<?php if ($status === 'success') : ?>
        <div id="popup" class="popup">Changes saved successfully!</div>
        <script>
         
            const popup = document.getElementById('popup');
            popup.style.display = 'block';
            

            setTimeout(() => {
                popup.style.display = 'none';
        
                const url = new URL(window.location.href);
                url.searchParams.delete('status');
                window.history.replaceState({}, document.title, url);
            }, 3000);
        </script>
         <style>
        .popup {
            position: fixed;
            top: 50%;
            right: 40%;
            background-color: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
        }
    </style>
    <?php endif; ?>
<div class="centered">
<div id="successPopup" class="popup-overlay" style="display: none;">
    <div class="popup-content"  style = "background-color: red;">
        <p>Article deleted successfully!</p>
    </div>
</div>
<div class="button-container">
    <button class="j" onclick="showMyArticles()">My Articles</button>
    <button class="j" onclick="showFavourites()">Favourites</button>
</div>
</div>
<hr>
<div id="my-articles" class="asd">
    <h1>My Articles Content</h1>
    <button class="jhj" onclick="checkProfileBeforeCreatePost()">Create Post</button>
    <ul id="articles-list"></ul>
    <p id="articles-count"></p>
</div>
<div id="confirmPopup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <p>Are you sure you want to delete this article?</p>
        <button id="confirmDelete" class="btn btn-danger">Confirm</button>
        <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
    </div>
</div>
<div id="favourites" class="asd" style="display: none;">
    <h1>Favourites</h1>
    <div id="favourites-content">
  
    </div>
</div>

<div id="removeFavoritePopup" class="popup-overlay" style="display: none;">
    <div class="popup-content" style="background-color: red;">
        <p>Article removed from favorites!</p>
    </div>
</div>
<script>
    
function showMyArticles() {
    document.getElementById('my-articles').style.display = 'block'; 
    document.getElementById('favourites').style.display = 'none'; 
}

function showFavourites() {
    document.getElementById('my-articles').style.display = 'none'; 
    document.getElementById('favourites').style.display = 'block'; 
}
function fetchFavorites() {
    fetch('fetch_favorites.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data:', data); 
            const favouritesContent = document.getElementById('favourites-content');
            favouritesContent.innerHTML = '';

            if (data.status === 'success') {
                if (data.favourites.length > 0) { 
                    data.favourites.forEach(favorite => {
                     
const favoriteContainer = document.createElement('div');
favoriteContainer.classList.add('favorites-container');
favoriteContainer.style.display = 'flex'; 
const favoriteElement = document.createElement('a');
favoriteElement.textContent = favorite; 
favoriteElement.href = `view_article.php?title=${encodeURIComponent(favorite)}`;
favoriteElement.style.color = 'black';
favoriteElement.style.textDecoration = 'none'; 
favoriteElement.style.position = 'relative'; 
favoriteElement.style.marginLeft = '20px';


const removeButton = document.createElement('button');
removeButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 21V5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 2 2v11.5" />
        <path d="M6 17l5-5 5 5" />
        <path d="M2 2l20 20" />
    </svg>
`;
removeButton.classList.add('btn', 'btn-danger');
removeButton.style.marginLeft = 'auto';
removeButton.onclick = function() {
    removeFavorite(favorite);
};

favoriteContainer.appendChild(favoriteElement);
favoriteContainer.appendChild(removeButton);

favouritesContent.appendChild(favoriteContainer);


const hrElement = document.createElement('hr');
favouritesContent.appendChild(hrElement);
                    });
                } else {
                    favouritesContent.innerHTML = '<p>No favorite articles found.</p>';
                }
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error fetching favorites:', error));  
}


function removeFavorite(favorite) {
    fetch('remove_favorite.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ title: favorite }) 
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
      
            const popup = document.getElementById('removeFavoritePopup');
            popup.style.display = 'flex';

            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000);

            fetchFavorites();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error removing favorite:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    fetchArticles(); 
    fetchFavorites();
});
</script>
    <script>
  
    document.getElementById('profile-picture-form').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(this);

        fetch('upload_profile_picture.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('profile-picture').src = data.filePath;

  
                const userName = document.getElementById('user-name').value;
                document.getElementById('display-name').textContent = userName;

                fetch('update_user_name.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: userName })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                
                        document.getElementById('user-name').style.display = 'none';
                        document.getElementById('profile-picture-upload').style.display = 'none';
                        document.getElementById('profile-picture-form').querySelector('button[type="submit"]').style.display = 'none';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    document.getElementById('edit-name-btn').addEventListener('click', function() {
        const nameInputContainer = document.getElementById('name-input-container');
        nameInputContainer.style.display = 'block';
        document.getElementById('new-name').value = '';
    });


    document.getElementById('save-name-btn').addEventListener('click', function() {
        const newName = document.getElementById('new-name').value; 
        if (newName) {
            fetch('newname.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: newName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('display-name').textContent = newName;
                    document.getElementById('name-input-container').style.display = 'none'; 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });


document.getElementById('edit-pic-btn').addEventListener('click', function() {
    const formData = new FormData();
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.onchange = function() {
        formData.append('profile_picture_upload', fileInput.files[0]);
        fetch('newpic.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('profile-picture').src = data.filePath;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    };
    fileInput.click();
});
function fetchArticles() {
    fetch('fetch_articles.php')
    .then(response => response.json())
    .then(data => {
        console.log('Fetched data:', data); 
        if (data.status === 'success') {
            const articles = data.articles;
            const count = data.count;

            const articlesList = document.getElementById('articles-list');
            articlesList.innerHTML = ''; 

            articles.forEach((article) => {
                const articleElement = document.createElement('li');
                articleElement.classList.add('article-element', 'd-flex', 'justify-content-between', 'align-items-center');

           
                const articleLink = document.createElement('a');
                articleLink.href = `view_article.php?title=${encodeURIComponent(article.title)}`;
                articleLink.textContent = article.title;
                articleLink.classList.add('article-link');

                const viewCountSpan = document.createElement('span');
                viewCountSpan.textContent = ` | Views: ${article.view_count}`;
                viewCountSpan.classList.add('view-count');
                
             
                articleElement.appendChild(articleLink);
                articleElement.appendChild(viewCountSpan);

         
                const deleteButton = document.createElement('span');
deleteButton.innerHTML = '<i class="fa-solid fa-trash"></i>'; 
deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
deleteButton.style.cursor = 'pointer';
                deleteButton.onclick = (function(article) {
                    return function() {
             
                        document.getElementById('confirmPopup').style.display = 'flex';

                        document.getElementById('confirmDelete').onclick = function() {
                            deleteArticle(article); 
                            document.getElementById('confirmPopup').style.display = 'none'; 
                        };

                   
                        document.getElementById('cancelDelete').onclick = function() {
                            document.getElementById('confirmPopup').style.display = 'none'; 
                        };
                    };
                })(article);

                
                const editButton = document.createElement('span');
editButton.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>'; 
editButton.classList.add('btn', 'btn-warning', 'btn-sm');
editButton.style.cursor = 'pointer'; 
editButton.onclick = (function(article) {
    return function() {
        location.href = `edit_article.php?title=${encodeURIComponent(article.title)}`;
    };
})(article);

           
                articleElement.appendChild(deleteButton);
                articleElement.appendChild(editButton);

          
                const hrElement = document.createElement('hr');
                articlesList.appendChild(articleElement);
                articlesList.appendChild(hrElement);
            });

            const countElement = document.getElementById('articles-count');
            countElement.textContent = `You have ${count} articles`;
        } else {
            console.error('Error in data:', data); 
            alert(data.message);
        }
    })
    .catch(error => console.error('Fetch error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    fetchArticles(); 
});

function deleteArticle(articleData) {
    const articleTitle = articleData.title;

    if (typeof articleTitle !== 'string' || articleTitle.trim() === "") {
        console.error("Invalid article title:", articleTitle);
        alert("Article title is invalid or empty.");
        return;
    }

    console.log('Attempting to delete article with title:', articleTitle); 
    fetch('delete_article.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ title: articleTitle })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.status === 'success') {
         
            fetchArticles();

      
            document.getElementById('successPopup').style.display = 'flex';
            setTimeout(() => {
                document.getElementById('successPopup').style.display = 'none';
            }, 2000); 
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting article:', error);
        alert('Error deleting article. Please try again.');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    fetchArticles(); 
});
function checkProfileBeforeCreatePost() {
    console.log("Checking profile...");
    $.ajax({
        type: "POST",
        url: "check_profile.php",
        data: {email: "<?php echo $_SESSION['email']; ?>"},
        success: function(response) {
            console.log("Response: " + response);
            if (response == "complete") {
                console.log("Profile complete, redirecting to createpost.php");
                location.href = 'createpost.php';
            } else {
                console.log("Profile incomplete, showing alert");
                alert("Please complete your profile picture and name first!");
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        }
    });
}
function showLogoutConfirmPopup() {
    document.getElementById('logoutConfirmPopup').style.display = 'flex'; 

document.getElementById('confirmLogout').addEventListener('click', function() {
    location.href = 'logout.php'; 
});

document.getElementById('cancelLogout').addEventListener('click', function() {
    document.getElementById('logoutConfirmPopup').style.display = 'none'; 
});
}
</script>
<style>
      .icon {
      width: 20px;
      height: 20px;
    }
</style>
</body>
</html>
