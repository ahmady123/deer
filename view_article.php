<?php
session_start();
include("connect.php");

if (!isset($_GET['title'])) {
    die("No article title provided");
}

$articleTitle = $_GET['title'];
$query = "SELECT article_template, users.name, users.profile_picture, users.id AS author_id FROM upload_articles 
          JOIN users ON users.id = upload_articles.user_id 
          WHERE title = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $articleTitle);
$stmt->execute();
$stmt->bind_result($articleTemplate, $authorName, $profilePicture, $authorId); 

if ($stmt->fetch()) {
    $stmt->free_result();
    $stmt->close();

    $articleAuthorId = $authorId; 
    ?>
   <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($articleTitle); ?></title>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="icon" href="mammal.png">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="adfg.css">
        <style>
        ::selection {
            background-color:rgb(132, 224, 204); 
            color: black; 
        }
        ::-moz-selection {
            background-color:rgb(132, 224, 204); 
            color: black;
        }
        .comment {
    display: flex;
    align-items: flex-start; 
    margin-bottom: 20px; 
    padding: 15px; 
    border-radius: 8px; 
    background-color: #f8f9fa; 
    transition: background-color 0.3s; 
}

.comment:hover {
    background-color: #e9ecef; 
}

.comment img {
    border-radius: 50%; 
    margin-right: 15px; 
    width: 50px; 
    height: 50px; 
}

.comment p {
    margin: 0; 
    color: #555;
    font-family: 'Arial', sans-serif; 
}

.comment p strong {
    margin-right: 30px;
    color: #007bff;
    font-weight: bold; 
    font-size: 14px; 
    margin-bottom: 15px;
}

.comment-text {
    margin-top: 5px; 
    font-size: 16px; 
    line-height: 1.6;
}

.modal-overlay {
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; 
}

.modal-content {
    top: 35%;
    background-color: white; 
    padding: 20px; 
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 400px;
    max-width: 90%;
    position: relative;
    margin: auto; 
    max-height: 80%; 
    overflow-y: auto; 
}

.comment {
    display: flex;
    align-items: flex-start; 
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px; 
    background-color: #f8f9fa; 
    transition: background-color 0.3s; 
}

@media (min-width: 768px) {
    .comment {
        padding: 20px; 
        margin-bottom: 25px;
    }

    .comment img {
        width: 60px;
        height: 60px; 
    }

    .comment p {
        font-size: 16px; 
    }
}

@media (min-width: 992px) {
    .comment {
        padding: 25px; 
        margin-bottom: 30px; 
    }

    .comment img {
        width: 70px; 
        height: 70px; 
    }

    .comment p {
        font-size: 18px;
    }
}

@media (max-width: 767px) {
    .comment {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
    }

    .comment img {
        width: 50px;
        height: 50px; 
    }

    .comment p {
        font-size: 14px;
    }
}
.nav-link, .button, .input-field{
    color: #000 !important;
    font-weight: bold;
}
.btn {
    background-color: #4b50dd;
    color: white; 
    border: 2px solid #4b50dd; 
    padding: 8px 16px;
    border-radius: 30px; 
    font-size: 14px; 
    font-weight: bold; 
    text-transform: uppercase;
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease; 
    cursor: pointer; 
}

.btn:hover {
    background-color: white;
    color: #4b50dd; 
    transform: scale(1.05); 
}
@media (max-width: 600px) {
    .btn {
        padding: 5px 10px; 
        font-size: 12px; 
    }
    .article-container{
        margin-top: 50px;
    }
}
.delete-comment, 
.reply-comment, 
.delete-reply-btn, 
.show-replies{
    background: none; 
    border: none; 
    color: #007bff;
    text-decoration: none; 
    cursor: pointer; 
    padding: 5px 10px; 
    font: inherit;
    border-radius: 5px; 
    margin-left: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.delete-comment:hover, 
.reply-comment:hover, 
.delete-reply-btn:hover, 
.show-replies:hover {
    color: #0056b3; 
    background-color: #f0f8ff;
    text-decoration: underline; 
    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.5);
}

.delete-comment:active, 
.reply-comment:active, 
.delete-reply-btn:active, 
.show-replies:active
.views-count:active {
    color: #003f7f; 
    background-color: #e6f2ff;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
}

.delete-comment {
    color: #dc3545; 
}

.delete-comment:hover {
    color: #a71d2a; 
    background-color: #f8d7da; 
}
.article-container img {

  height: 450px;

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
                    <li>
   </div>
   <div class="input-container">
    <input type="text" id="search-input" class="input-field" placeholder="Search for articles..." autocomplete="off">
    <span class="input-highlight"></span>
</div>
    <div id="search-results" class="search-results"></div> 
</li>
                </ul>
                </nav>
        
    <body>
        <br>
    <div class="article-container">
        <div class="article-content">
            <?php echo $articleTemplate; ?>
        </div>
        
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $articleAuthorId): ?>
                <a href="edit_article.php?title=<?php echo urlencode($articleTitle); ?>" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                <button id="delete-article" class="btn btn-danger" data-article-title="<?php echo htmlspecialchars($articleTitle); ?>"><i class="fa-solid fa-trash"></i></button>
     


                <div id="delete-confirmation-modal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this article?</p>
        <div class="modal-buttons">
            <button id="confirm-delete-article" class="btn btn-danger">Delete</button>
            <button id="cancel-delete-article" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>
                <?php else: ?>
                    <p></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?> 
    <button id="add-favorite-button" class="btn btn-warning" data-article-title="<?php echo htmlspecialchars($articleTitle); ?>"> 
    <i class="fa-solid fa-bookmark"></i>
    </button> 
    <div id="favorite-popup" style="display: none; position: fixed; top: 50%; right: 50%; background-color: #4CAF50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
    Artilce added to favorites! View in your profile
</div>
<div id="remove-popup" style="display: none; position: fixed; top: 50%; right: 50%; background-color: red; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
Article removed from favourites
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const favoriteButton = document.getElementById('add-favorite-button'); 

    favoriteButton.addEventListener('click', function() {

        const popup = document.getElementById('favorite-popup');
        popup.style.display = 'block';

        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const favoriteButton = document.getElementById('remove-favorite-button'); 

    favoriteButton.addEventListener('click', function() {

        const popup = document.getElementById('remove-popup');
        popup.style.display = 'block';

        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    });
});
</script>
<style>
      .icon {
      width: 20px;
      height: 20px;
    }
</style>
    <button id="remove-favorite-button" class="btn btn-danger" data-article-title="<?php echo htmlspecialchars($articleTitle); ?>" > 
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M6 21V5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 2 2v11.5" />
    <path d="M6 17l5-5 5 5" />
    <path d="M2 2l20 20" />
  </svg>
    </button> 
<?php endif; ?>
<button id="download-button" class="btn btn-success"><i class="fa-solid fa-download"></i></button>
<button id="share-button" class="btn btn-primary"><i class="fa-solid fa-share"></i></button>
<?php

include("connect.php"); 

function incrementViewCount($article_id) {
    global $conn; 

    $sql = "UPDATE upload_articles SET view_count = view_count + 1 WHERE article_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $article_id); 
        $stmt->execute(); 
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

if (isset($_GET['title'])) {
    $title = $_GET['title'];

    $sql = "SELECT article_id, user_id FROM upload_articles WHERE title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title); 
    $stmt->execute();
    $stmt->bind_result($article_id, $user_id); 
    $stmt->fetch();
    $stmt->close();

    if ($article_id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $user_id) {
            incrementViewCount($article_id);
        }

        $sql = "SELECT view_count FROM upload_articles WHERE article_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $stmt->bind_result($view_count);
        $stmt->fetch();
        $stmt->close();

        echo "<span class='views-count'>Views: " . $view_count . "</span>";
    } else {
        echo "Article not found.";
    }
} else {
    echo "Article title not specified.";
}
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const downloadButton = document.getElementById('download-button');

    downloadButton.addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const articleTitle = "<?php echo addslashes(htmlspecialchars($articleTitle)); ?>"; 
        const articleContentElement = document.querySelector('.article-content');
        const articleContent = articleContentElement.innerText.trim();

        doc.setFont("times", "normal");
        doc.setFontSize(18); 
        const pageWidth = doc.internal.pageSize.getWidth();
        const titleWidth = doc.getTextWidth(articleTitle);
        const titleX = (pageWidth - titleWidth) / 2; 
        doc.text(articleTitle, titleX, 20); 

        const firstImage = articleContentElement.querySelector('img');
        if (firstImage) {
            const imageURL = firstImage.src;

            const tempImage = new Image();
            tempImage.src = imageURL;
            tempImage.onload = function () {
                const pdfWidth = pageWidth; 
                const pdfHeight = 100; 
                const imageX = 0; 

                doc.addImage(tempImage, 'JPEG', imageX, 30, pdfWidth, pdfHeight);

            
                addContentAfterImage(doc, articleContent, 30 + pdfHeight + 10, articleTitle);
            };
        } else {
            addContentAfterImage(doc, articleContent, 30, articleTitle);
        }
    });

    function addContentAfterImage(doc, articleContent, startY, articleTitle) {
        const maxLineWidth = doc.internal.pageSize.getWidth();
        const maxPageHeight = doc.internal.pageSize.getHeight() - 20;

        const lineHeight = 8;
        let currentHeight = startY;

        const lines = doc.splitTextToSize(articleContent, maxLineWidth);

        doc.setFontSize(12);
        doc.setFont("times", "normal");

        lines.forEach((line) => {
            if (line.startsWith("##")) { 
                doc.setFontSize(16);
                doc.setFont("times", "bold");
                line = line.substring(2).trim();
            }

            const textWidth = doc.getTextWidth(line);
            const textX = (maxLineWidth - textWidth) / 2;

            if (currentHeight + lineHeight > maxPageHeight) {
                doc.addPage();
                currentHeight = 10;
            }
            doc.text(line, textX, currentHeight);
            currentHeight += lineHeight;

            doc.setFontSize(12);
            doc.setFont("times", "normal");
        });

        doc.save(`${articleTitle}.pdf`);
    }
});
    document.addEventListener('DOMContentLoaded', function () {
    const shareButton = document.getElementById('share-button');
    const articleTitle = "<?php echo htmlspecialchars($articleTitle); ?>";
    const articleUrl = window.location.href;

    shareButton.addEventListener('click', function () {
        if (navigator.share) {
            
            navigator.share({
                title: articleTitle,
                url: articleUrl
            })
            .then(() => console.log('Share successful'))
            .catch((error) => console.error('Error sharing:', error));
        } else {
            const shareText = `Check out this article: ${articleTitle} - ${articleUrl}`;
            prompt("Copy this link to share:", shareText);
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const addFavoriteButton = document.getElementById('add-favorite-button');
    const removeFavoriteButton = document.getElementById('remove-favorite-button');

    if (addFavoriteButton && removeFavoriteButton) {
        const articleTitle = addFavoriteButton.getAttribute('data-article-title');

        fetch('get_favorite_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `title=${encodeURIComponent(articleTitle)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.isFavorite) {
                    addFavoriteButton.style.display = 'none';
                    removeFavoriteButton.style.display = 'inline-block';
                } else {
                    addFavoriteButton.style.display = 'inline-block';
                    removeFavoriteButton.style.display = 'none';
                }
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching favorite status:', error));

        addFavoriteButton.addEventListener('click', function () {
            fetch('toggle_favourite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `title=${encodeURIComponent(articleTitle)}&action=add`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addFavoriteButton.style.display = 'none';
                    removeFavoriteButton.style.display = 'inline-block';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error adding to favorites:', error));
        });

        removeFavoriteButton.addEventListener('click', function () {
            fetch('toggle_favourite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `title=${encodeURIComponent(articleTitle)}&action=remove`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    removeFavoriteButton.style.display = 'none';
                    addFavoriteButton.style.display = 'inline-block';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error removing from favorites:', error));
        });
    }
});

</script>
        <br><br>
        <div class="author-info" style="text-align: center;">
            <p style="display: flex; align-items: center; justify-content: center;">
                by 
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="<?php echo htmlspecialchars($authorName); ?>'s profile picture" style="margin: 0 8px; max-height: 40px;">
                <?php echo htmlspecialchars($authorName); ?>
            </p>
        </div>
    </div>

    <?php
if (isset($_SESSION['user_id'])) {
    $currentUserId = $_SESSION['user_id'];
} else {
    $currentUserId = null; 
}
?>
<div class="comments-section">
    <h3>Comments</h3>
    <div id="comments-list">
        <?php
        $commentQuery = "SELECT comments.id, comments.comment, comments.created_at, users.name, users.profile_picture, users.id AS author_id
                         FROM comments 
                         JOIN users ON users.id = comments.user_id 
                         WHERE comments.article_title = ? ORDER BY comments.created_at DESC";
        $commentStmt = $conn->prepare($commentQuery);

        if (!$commentStmt) {
            die('Comment prepare failed: ' . $conn->error);
        }

        $commentStmt->bind_param("s", $articleTitle);
        $commentStmt->execute();
        $commentStmt->store_result(); 
        $commentStmt->bind_result($commentId, $commentText, $createdAt, $commentAuthor, $commentAuthorPicture, $commentAuthorId);

        while ($commentStmt->fetch()) {
        ?>
        <div class="comment">
            <img src="<?php echo htmlspecialchars($commentAuthorPicture); ?>" alt="<?php echo htmlspecialchars($commentAuthor); ?>" style="max-height: 40px;">
            <p><strong><?php echo htmlspecialchars($commentAuthor); ?>:</strong></p>
            <p class="comment-text"><?php echo nl2br(htmlspecialchars($commentText)); ?></p>
            <?php if ($currentUserId == $commentAuthorId) { ?>
                <button class="delete-comment" data-comment-id="<?php echo $commentId; ?>">Delete<i class="fa-solid fa-trash"></i></button>
               
     
            <?php } ?>
            <?php if (isset($_SESSION['user_id'])):  ?>
    <?php
    $userId = $_SESSION['user_id'];
    $profileQuery = "SELECT name FROM users WHERE id = ?";
    $profileStmt = $conn->prepare($profileQuery);
    $profileStmt->bind_param("i", $userId);
    $profileStmt->execute();
    $profileStmt->bind_result($profileComplete);
    $profileStmt->fetch();
    $profileStmt->close();
    ?>

    <button class="reply-comment" data-comment-id="<?php echo $commentId; ?>">Reply</button> 

    <?php if ($profileComplete): ?>
        <div class="reply-form" id="reply-form-<?php echo $commentId; ?>" style="display:none;">
            <textarea class="form-control" placeholder="Add your reply..." style="height: 50px;"></textarea>
            <button class="btn-primary submit-reply" data-comment-id="<?php echo $commentId; ?>">Post Reply</button>
        </div>
    <?php else: ?>
        <p>You must complete your profile first to reply.</p>
    <?php endif; ?>
<?php else: ?>
   
<?php endif; ?>
       <button class="show-replies" data-comment-id="<?php echo $commentId; ?>">Show Replies</button>
        <div class="replies-list" id="replies-list-<?php echo $commentId; ?>" style="display:none;">
    <?php
    $replyQuery = "SELECT replies.id, reply_text, created_at, users.name, users.profile_picture, replies.user_id AS replyAuthorId
                   FROM replies 
                   JOIN users ON users.id = replies.user_id 
                   WHERE replies.comment_id = ? 
                   ORDER BY replies.created_at ASC";
    $replyStmt = $conn->prepare($replyQuery);

    if (!$replyStmt) {
        die('Reply prepare failed: ' . $conn->error);
    }

    $replyStmt->bind_param("i", $commentId);
    $replyStmt->execute();
    $replyStmt->store_result(); 
    $replyStmt->bind_result($replyId, $replyText, $replyCreatedAt, $replyAuthorName, $replyAuthorPicture, $replyAuthorId);

    while ($replyStmt->fetch()) {
      if (isset($_SESSION['user_id'])) {
        $isAuthor = ($_SESSION['user_id'] == $replyAuthorId);
      }else{
        $isAuthor = false;
      }
    ?>
        <div class="reply">
            <img src="<?php echo htmlspecialchars($replyAuthorPicture); ?>" alt="<?php echo htmlspecialchars($replyAuthorName); ?>" style="max-height: 30px;">
            <p><strong><?php echo htmlspecialchars($replyAuthorName); ?>:</strong></p>
            <p class="reply-text"><?php echo nl2br(htmlspecialchars($replyText)); ?></p>

            <?php if ($isAuthor): ?>
                <button class="delete-reply-btn" data-reply-id="<?php echo $replyId; ?>">Delete</button>
            <?php endif; ?>
        </div>
        <div id="confirm-delete-popup" class="confirm-delete-popup hidden">
    <div class="confirm-popup-content">
        <p>Are you sure you want to delete this reply?</p>
        <button  class = "btn-danger" id="confirm-delete-btn">Yes, Delete</button>
        <button class = "btn-primary" id="cancel-delete-btn">Cancel</button>
    </div>
</div>

<style>
.reply {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px; 
    padding: 10px;
    border-radius: 6px; 
    background-color: #dee2e6; 
    transition: background-color 0.3s; 
}

.reply:hover {
    background-color: #ced4da;
}

@media (max-width: 767px) {
    .reply {
        flex-direction: column; 
        padding: 8px; 
    }

  

    .reply p {
        font-size: 14px; 
    }
}
.confirm-delete-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
.confirm-delete-popup.hidden {
    display: none;
}
.confirm-popup-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}
</style>

    <?php
    }

    $replyStmt->free_result();
    $replyStmt->close();
    ?>
</div>

</div>


        
<div id="confirmation-modal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this comment?</p>
        <div class="modal-buttons">
            <button id="confirm-delete" class="btn btn-danger">Delete</button>
            <button id="cancel-delete" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>


                <?php
            }
    

            ?>
        </div>
    </div>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit-comment').addEventListener('click', function() {
        const commentText = document.getElementById('comment-text').value.trim();
        
        if (commentText !== "") {
            fetch('submit_comment.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'comment_text': commentText,
                    'article_title': '<?php echo addslashes($articleTitle); ?>'
                }),
                credentials: 'same-origin',
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    location.reload();
                } else {
                    alert("Error posting comment");
                }
            })
            .catch(error => console.error('Error submitting comment:', error));
        }
    });
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
const inputContainer = document.querySelector('.input-container');
    const searchResults = document.getElementById('search-results');


    document.addEventListener('click', (event) => {
 
        if (!inputContainer.contains(event.target)) {
  
            searchResults.style.display = 'none';
        }
    });

document.addEventListener("DOMContentLoaded", function () {
    let commentIdToDelete = null;

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-comment")) {
            commentIdToDelete = e.target.getAttribute("data-comment-id");
            document.getElementById("confirmation-modal").style.display = "block";
            document.getElementById("modal-overlay").style.display = "block";
        }
    });

    document.getElementById("cancel-delete").addEventListener("click", function () {
        document.getElementById("confirmation-modal").style.display = "none";
        document.getElementById("modal-overlay").style.display = "none";
        commentIdToDelete = null;
    });

    document.getElementById("confirm-delete").addEventListener("click", function () {
        if (commentIdToDelete) {
            fetch("delete_comment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `comment_id=${commentIdToDelete}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        const commentElement = document.querySelector(`.delete-comment[data-comment-id="${commentIdToDelete}"]`).closest(".comment");
                        if (commentElement) {
                            commentElement.remove();
                        }
                    } else {
                        alert("Failed to delete the comment.");
                    }
                    document.getElementById("confirmation-modal").style.display = "none";
                    document.getElementById("modal-overlay").style.display = "none";
                    commentIdToDelete = null;
                })
                .catch((error) => console.error("Error deleting comment:", error));
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const commentTextArea = document.getElementById('comment-text');

    if (commentTextArea) {
        commentTextArea.addEventListener('paste', function(event) {
            event.preventDefault();
            
            const pastedData = (event.clipboardData || window.clipboardData).getData('Text');

            commentTextArea.value += pastedData;
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-reply-btn');
    const popup = document.getElementById('confirm-delete-popup');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    let selectedReplyId = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            selectedReplyId = this.getAttribute('data-reply-id');
            popup.classList.remove('hidden');
        });
    });

    cancelDeleteBtn.addEventListener('click', function () {
        popup.classList.add('hidden');
        selectedReplyId = null;
    });

    confirmDeleteBtn.addEventListener('click', function () {
        if (selectedReplyId) {
            fetch('delete_reply.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reply_id: selectedReplyId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
    
                    document.querySelector(`[data-reply-id="${selectedReplyId}"]`).closest('.reply').remove();
                } else {
                    alert(data.message || 'Error deleting reply');
                }
                popup.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the reply.');
                popup.classList.add('hidden');
            });
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reply-comment').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.submit-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyTextArea = document.querySelector(`#reply-form-${commentId} textarea`);
            const replyText = replyTextArea.value.trim();

            if (replyText !== "") {
                fetch('submit_reply.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'reply_text': replyText,
                        'comment_id': commentId
                    }),
                    credentials: 'same-origin',
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert("Error posting reply");
                    }
                })
                .catch(error => console.error('Error submitting reply:', error));
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-replies').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const repliesList = document.getElementById(`replies-list-${commentId}`);
            if (repliesList.style.display === 'none') {
                repliesList.style.display = 'block'; 
                this.textContent = 'Hide Replies';
            } else {
                repliesList.style.display = 'none';
                this.textContent = 'Show Replies';
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('delete-article');
    const deleteModal = document.getElementById('delete-confirmation-modal');
    const confirmDeleteButton = document.getElementById('confirm-delete-article');
    const cancelDeleteButton = document.getElementById('cancel-delete-article');

    deleteButton.addEventListener('click', function() {
        deleteModal.style.display = 'block'; 
    });

    cancelDeleteButton.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    confirmDeleteButton.addEventListener('click', function() {
        const articleTitle = deleteButton.getAttribute('data-article-title');
        fetch('dele.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `title=${encodeURIComponent(articleTitle)}`,
})
.then(response => response.json())
.then(data => {
    console.log(data); 
    if (data.success) {
        window.location.href = 'index.php?deleted=true';
    } else {
        alert(data.message || 'Failed to delete the article.');
    }
})
.catch(error => console.error('Error deleting article:', error));
});
});
</script>
        <?php if (isset($_SESSION['user_id'])): ?>
    <?php
    $userId = $_SESSION['user_id'];
    $profileQuery = "SELECT name FROM users WHERE id = ?";
    $profileStmt = $conn->prepare($profileQuery);
    $profileStmt->bind_param("i", $userId);
    $profileStmt->execute();
    $profileStmt->bind_result($profileComplete);
    $profileStmt->fetch();
    $profileStmt->close();
    ?>

    <?php if ($profileComplete): ?>
        <form method="POST" action="">
            <textarea id="comment-text" name="comment_text" class="form-control" placeholder="Add your comment..." style="height: 100px;"></textarea><br>
            <button type="submit" id="submit-comment" class="btn btn-primary">Post Comment</button>
        </form>
    <?php else: ?>
        <p>You must complete your profile first.</p>
    <?php endif; ?>
<?php else: ?>
    <p>You must be logged in to post a comment.</p>
<?php endif; ?>
    </div>
    </body>
    </html>
<?php
}