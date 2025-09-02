<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "signup";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$articles = [];

$limit = 20;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$sql = "SELECT a.title, a.image_path, u.name AS authorName, u.profile_picture AS profilePicture 
        FROM upload_articles a 
        JOIN users u ON a.user_id = u.id 
        ORDER BY a.created_at DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        $articles[] = $row; 
    }
} else {
    error_log("SQL Error: " . $conn->error);
}

if (isset($_GET['ajax'])) {
    echo json_encode($articles);
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deer</title>
    <link rel="icon" href="mammal.png">
    <link rel="stylesheet" href="home.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <script src="home.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap');
    .center-container {
  display: flex; 
  justify-content: center; 
  align-items: center; 
 margin-top: 10px;
  background-color: #f8f9fa; 
}

#show-more {
  width: 9em; 
  height: 3em;
  border-radius: 30em; 
  font-size: 15px; 
  font-family: inherit; 
  border: none; 
  position: relative;
  overflow: hidden;
  z-index: 1; 
  box-shadow: 6px 6px 12px #c5c5c5, 
              -6px -6px 12px #ffffff; 
  cursor: pointer; 
  transition: color 0.3s ease; 
}

#show-more::before {
  content: ''; 
  width: 0; 
  height: 3em;
  border-radius: 30em;
  position: absolute;
  top: 0;
  left: 0;
  background-image: linear-gradient(to right, #0fd850 0%, #f9f047 100%);
  transition: .5s ease;
  display: block;
  z-index: -1; 
}

#show-more:hover::before {
  width: 9em; 
}
@media (max-width: 760px) {
.search-results{
left: 40%;
}
}
@media (max-width: 768px) {
    .carousel-item::before {
      
        background-color: #c4e4be;
    }
    
}
@media (max-width: 1200px) {
    .button1 {
        font-size: 1rem !important; 
        padding: 5px 10px !important;
    }
}
.nav-link, .button, .input-field{
    color: #000 !important;
    font-weight: bold;
}
@media (min-width: 768px) {
    #button2 {
        margin-left: 10px;
    }
}
#successPopup {
            display: none;
            position: fixed;
            top: 50%;
            right: 50%;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
        }
        .articles-container {
    padding: 60px 0; /* Add more vertical spacing for a spacious look */
    background-color: #f8f9fa; /* Subtle background for better readability */
}

.articles-container .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.articles-container h2 {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 40px;
}

.articles-container .article-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
    grid-gap: 30px;
}

.articles-container .article {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.articles-container .article:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.articles-container .article img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-bottom: 3px solid #007bff; /* Adds a colored border for style */
}

.articles-container .article-content {
    padding: 20px;
}

.articles-container .category {
    display: inline-block;
    background: #007bff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
}



.articles-container .article h3 {
    font-size: 1.5rem;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    color: #222;
    margin-bottom: 10px;
    transition: all 0.3s ease-in-out;
    display: inline-block;

}



.articles-container .author {
    font-size: 14px;
    color: #666;
   
    align-items: center;
    margin-top: 15px;
}

.author-image {
    width: 10px; /* Set the width to 10px */
    height: 10px; /* Set the height to 10px */
    border-radius: 50%; /* Keeps the image circular */
    margin-right: 5px; /* Adjust space between the image and the author's name */
    object-fit: cover; /* Ensures the image fits inside the circle without distortion */
    display: inline-block; /* Keeps the image inline with the author's name */
    vertical-align: middle; /* Aligns the image with the text */
}
.author-image {
  width: 20px !important; /* Force the width to 10px */
  height: 20px !important; /* Force the height to 10px */
  border-radius: 50% !important; /* Keeps the author image circular */
 
}

@media (max-width: 768px) {
    .articles-container {
        padding: 40px 20px;
    }

    .articles-container .article-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        grid-gap: 20px;
    }

    .articles-container .article img {
        height: 180px;
    }
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
        <li class="nav-item">
            <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i>Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="aboutus.php">About Us</a>
        </li>
        <?php if (isset($_SESSION['email'])): ?>
        <li class="nav-item">
            <button class="button nav-link" id="button1" onclick="location.href='profile.php'">
                <i class="fa-solid fa-user"></i> Profile
            </button>
        </li>
        <li class="nav-item">
            <button class="button nav-link" id="button2" onclick="toggleLogoutPopup()">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </li>
        <?php else: ?>
        <li class="nav-item">
            <button class="button nav-link" id="button1" onclick="startSession()">
            <i class="fa-solid fa-right-to-bracket"></i>   Register/Login
            </button>
        </li>
        <?php endif; ?>
    </ul>
    <div id="logout-confirmation" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <p>Are you sure you want to log out?</p>
            <button id="confirm-logout" class="btn btn-danger">Confirm</button>
            <button id="cancel-logout" class="btn btn-secondary" onclick="toggleLogoutPopup()">Cancel</button>
        </div>
    </div>
</div>

    <div class="input-container">
                        <input type="text" id="search-input" class="input-field" placeholder="Search for articles..." autocomplete="off">
                        <span class="input-highlight"></span>
                    </div>
</nav>

<div id="search-results" class="search-results"></div> 
    <div class="carousel-container container-fluid">
        <div id="customCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
            
            <ol class="carousel-indicators">
                <li data-bs-target="#customCarousel" data-bs-slide-to="0" class="active"></li>
                <li data-bs-target="#customCarousel" data-bs-slide-to="1"></li>
                <li data-bs-target="#customCarousel" data-bs-slide-to="2"></li>
            </ol>

            
            <div class="carousel-inner" style="max-width: 100%; margin: 0 auto;">
                
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <img class="ki" src="AdobeStock_132869690-removebg-preview.png" alt="">
                            </div>
                            <div class="col-md-6">
                                <h1>Looking For a Website to study <br>Or share your thoughts</h1>
                                <h4>WE made a platform in which you can create your own blogs and share your thoughts with the world. Get started Now→</h4>
                                <br>
                                <?php if (isset($_SESSION['email'])): ?>
                                    <button class = "jhj"  onclick="checkProfileBeforeCreatePost()">
                                    Create Post
</button>
<?php else: ?>
                                <button class="button1" id="button" onclick="startSession()">
                                    <div class="blob1"></div>
                                    <div class="blob2"></div>
                                    <div class="inner">Register/Login</div>
                                </button>
                                <?php endif; ?>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <img class="ki" src="pexels-photo-3153198-removebg-preview (2).png" alt="">
                            </div>
                            <div class="col-md-6">
                                <h1>Share information and knowledge </h1>
                                <h4>Share new articles, blogs, and other content with the world. So that others can get notified. Connect with us→</h4>
                                <br>
                                <?php if (isset($_SESSION['email'])): ?>
                                    <button class = "jhj"   onclick="checkProfileBeforeCreatePost()">
                                    Create Post
</button>
<?php else: ?>
                                <button class="button1" id="button" onclick="startSession()">
                                    <div class="blob1"></div>
                                    <div class="blob2"></div>
                                    <div class="inner">Register/Login</div>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <img class="ki" src="pexels-photo-7710085-removebg-preview.png" alt="">
                            </div>
                            <div class="col-md-6">
                                <h1>Read our Articles and share with others</h1>
                                <h4>Read our articles and also share these with your friends. You can also comment on them.</h4>
                                <br>
                                <?php if (isset($_SESSION['email'])): ?>
                                    <button class = "jhj" onclick="checkProfileBeforeCreatePost()">
   Create Post
</button>
<?php else: ?>
                                <button class="button1" id="button" onclick="startSession()">
                                    <div class="blob1"></div>
                                    <div class="blob2"></div>
                                    <div class="inner">Register/Login</div>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <br><br>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('deleted')) {
   
        const popup = document.createElement('div');
        popup.textContent = 'Article deleted successfully!';
        popup.style.position = 'fixed';
        popup.style.top = '50%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';
        popup.style.backgroundColor = 'red';
        popup.style.color = 'white';
        popup.style.padding = '10px';
        popup.style.borderRadius = '5px';
        popup.style.zIndex = '9999999';
        document.body.appendChild(popup);
        setTimeout(() => {
            popup.remove();
        }, 3000);

       
        urlParams.delete('deleted');
        const newUrl = `${window.location.origin}${window.location.pathname}?${urlParams.toString()}`;
        window.history.replaceState({}, '', newUrl);
    }
});

  </script>
   <div id="successPopup" style="display: none;">Article uploaded successfully!</div>
<script>
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('upload') && urlParams.get('upload') === 'success') {
        const popup = document.getElementById('successPopup');
        popup.style.display = 'block';

        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);

        urlParams.delete('upload');
        const newUrl = `${window.location.origin}${window.location.pathname}?${urlParams.toString()}`;
        window.history.replaceState({}, '', newUrl);
    }
</script>

    <div class="articles-container">
  <div class="container">
    <h2>Articles</h2>
    <div class="article-grid" id="article-grid">
      <?php
      foreach ($articles as $row) {
          echo "<div class='article'>";
          echo "<a href='view_article.php?title=" . urlencode($row['title']) . "' style='text-decoration: none; color: inherit;'>";
          if (!empty($row['image_path'])) {
              echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "'>";
          }
          echo "<div class='article-content'>";
          echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
          if (!empty($row['profilePicture'])) {
              echo "<div class='author-info'>";
              echo "<img class='author-image' src='" . htmlspecialchars($row['profilePicture']) . "' alt='" . htmlspecialchars($row['authorName']) . "'>";
              echo "<span class='author'>by " . htmlspecialchars($row['authorName']) . "</span>";
              echo "</div>";
          } else {
              echo "<p class='author'>by " . htmlspecialchars($row['authorName']) . "</p>";
          }
          echo "</div>";
          echo "</a>";
          echo "</div>";
      }
      ?>
    </div>
    <div class="center-container">
    <button id="show-more">Show More</button>
</div>
  </div>
</div>
<div id="profile-incomplete-popup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <p>Please complete your profile picture and name first!</p>
        <button id="close-popup" class="btn btn-secondary">Close</button>
    </div>
</div>
    <script>
function checkProfileBeforeCreatePost() {
    console.log("Checking profile...");
    $.ajax({
        type: "POST",
        url: "check_profile.php",
        data: {email: <?php echo json_encode($_SESSION['email'] ?? ''); ?>},
        success: function(response) {
            console.log("Response: " + response);
            if (response == "complete") {
                console.log("Profile complete, redirecting to createpost.php");
                location.href = 'createpost.php';
            } else {
                console.log("Profile incomplete, showing popup");
                document.getElementById('profile-incomplete-popup').style.display = 'flex';
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        }
    });
}

document.getElementById('close-popup').addEventListener('click', function() {
    document.getElementById('profile-incomplete-popup').style.display = 'none';
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = this.value;

        if (query.length > 0) {
            fetch(`search_results.php?query=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    searchResults.innerHTML = data;
                    
                    if (data.trim() !== "") {
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
        } else {
            searchResults.innerHTML = ''; 
            searchResults.style.display = 'none';
        }
    });
});

function validateEmail(inputId, errorId) {
    const emailInput = document.getElementById(inputId);
    const errorMessage = document.getElementById(errorId);
    if (!emailInput || !errorMessage) {
        console.log(`Elements not found for ${inputId} or ${errorId}`);
        return; 
    }
    
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    emailInput.addEventListener('input', () => {
        const email = emailInput.value;
        if (emailPattern.test(email)) {
            errorMessage.textContent = 'Email format is valid';
            errorMessage.style.color = 'green';
        } else if (email !== "") {
            errorMessage.textContent = 'Invalid email format';
            errorMessage.style.color = 'red';
        } else {
            errorMessage.textContent = '';
        }
    });
}


function toggleForms() {
    const registerMessage = document.getElementById('registerMessage');
    const loginMessage = document.getElementById('loginMessage');
    const signUpSection = document.getElementById('signUpSection');
    const signInSection = document.getElementById('signInSection');

    if (registerMessage && loginMessage && signUpSection && signInSection) {
        registerMessage.style.display = registerMessage.style.display === 'none' ? 'block' : 'none';
        loginMessage.style.display = loginMessage.style.display === 'none' ? 'block' : 'none';
        signUpSection.style.display = signUpSection.style.display === 'none' ? 'block' : 'none';
        signInSection.style.display = signInSection.style.display === 'none' ? 'block' : 'none';
    } else {
        console.log('One or more elements for toggleForms not found');
    }
}


document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('email-signup') && document.getElementById('email-error-signup')) {
        validateEmail('email-signup', 'email-error-signup');
    }
    
    if (document.getElementById('email-signin') && document.getElementById('email-error-signin')) {
        validateEmail('email-signin', 'email-error-signin');
    }

    document.querySelectorAll('.nn, .switchSectionButton').forEach(button => {
        button.addEventListener('click', toggleForms);
    });
});
let offset = 20; 
const limit = 20; 

document.getElementById('show-more').addEventListener('click', function() {
    fetch(`index.php?ajax=true&offset=${offset}`)
        .then(response => response.json())
        .then(data => {
            const articleGrid = document.getElementById('article-grid');
            data.forEach(article => {
                const articleDiv = document.createElement('div');
                articleDiv.classList.add('article');
                articleDiv.innerHTML = `
                    <a href='view_article.php?title=${encodeURIComponent(article.title)}' style='text-decoration: none; color: inherit;'>
                        ${article.image_path ? `<img src='${article.image_path}' alt='${article.title}'>` : ''}
                        <div class='article-content'>
                            <h3>${article.title}</h3>
                            <div class='author-info'>
                                ${article.profilePicture ? `<img class='author-image' src='${article.profilePicture}' alt='${article.authorName}'>` : ''}
                                <span class ='author'>by ${article.authorName}</span>
                            </div>
                        </div>
                    </a>
                `;
                articleGrid.appendChild(articleDiv);
            });

            if (data.length < limit) {
                document.getElementById('show-more').style.display = 'none'; 
            }

            offset += limit;
        })
        .catch(error => console.error('Error fetching more articles:', error));
});
const inputContainer = document.querySelector('.input-container');
    const searchResults = document.getElementById('search-results');


    document.addEventListener('click', (event) => {
 
        if (!inputContainer.contains(event.target)) {
  
            searchResults.style.display = 'none';
        }
    });


    </script>
   
</body>
</html>
