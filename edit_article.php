<?php
session_start();
include("connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = $_GET['title'] ?? null; 

if (!$title) {
    die("No article specified."); 
}

$query = "SELECT article_id, title, content, image_path FROM upload_articles WHERE title = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error); 
}

$stmt->bind_param("s", $title); 
$stmt->execute();
$stmt->bind_result($article_id, $title, $content, $imagePath);
$stmt->fetch();
$stmt->close();

if (!$article_id) {
    die("Article not found."); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link rel="icon" href="mammal.png">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
     
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

form {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 600px;
    margin: auto;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

input[type="text"],
input[type="file"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
textarea:focus {
    border-color: #007bff;
    outline: none;
}

.image-preview {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 5px;
}


.btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn-danger{
    background-color:rgb(255, 0, 0);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    text-decoration: none !important;
}

.btn:hover {
    background-color: #0056b3;
}

@media (max-width: 600px) {
    form {
        padding: 15px;
    }

    .btn {
        width: 100%;
    }
}
#contentEditableDiv {
    min-height: 800px;
    overflow: auto;
    font-family: 'Times', 'Lora';
    color: black;
}
.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000; 
    background: white;
    border: 1px solid #ccc;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
    padding: 20px;
    border-radius: 8px;
}
.image-preview {
        width: 200px;
        height: 150px; 
        
        border: 1px solid #ccc; 
    }
    </style>
</head>
<body>
<form action="update_article.php" method="POST" enctype="multipart/form-data">
    
<div class="form-group">
        <label for="image">Upload Image:</label>
        <?php if ($imagePath): ?>
            <img id="currentImage" src="<?php echo htmlspecialchars($imagePath); ?>" alt="Article Image" class="image-preview">
        <?php endif; ?>
        <input type="file" name="image" id="image" class="form-control">
    </div>
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required maxlength="75"> 
    </div>
    
    <div class="form-group">
    <p>Select any text to make it heading or add a link.Or double tap on the text in mobile</p>
        <label for="content">Content:</label>
        <div id="contentEditableDiv" contenteditable="true" class="form-control" required><?php echo($content); ?></div>
    </div>

    <div id="toolbar" class="popup" style="display: none;">
        <button type="button" onclick="applyHeading('h2')">H2</button>
        <button type="button" onclick="applyHeading('h3')">H3</button>
        <button type="button" onclick="applyHeading('h4')">H4</button>
        <button type="button" onclick="showLinkPopup()">Insert Link</button>
    </div>

    <div id="linkPopup" class="popup" style="display: none;">
        <label for="linkUrl">URL:</label>
        <input type="text" id="linkUrl" placeholder="Enter URL">
        <button type="button" onclick="applyLink()">Apply Link</button>
        <button type="button" onclick="closeLinkPopup()">Close</button>
    </div>
    <script>
    document.getElementById('contentEditableDiv').addEventListener('mouseup', function () {
        const selection = window.getSelection();
        if (selection.toString().length > 0) {
            const rect = selection.getRangeAt(0).getBoundingClientRect();
            const toolbar = document.getElementById('toolbar');
            toolbar.style.top = rect.top + window.scrollY - toolbar.offsetHeight - 200 + 'px';
            toolbar.style.left = rect.left + window.scrollX + 'px';
            toolbar.style.display = 'block'; 
        } else {
            document.getElementById('toolbar').style.display = 'none';
        }
    });

    document.addEventListener('click', function (event) {
        const toolbar = document.getElementById('toolbar');
        const contentDiv = document.getElementById('contentEditableDiv');
        if (!contentDiv.contains(event.target) && !toolbar.contains(event.target)) {
            toolbar.style.display = 'none';
        }
    });

    function applyHeading(headingTag) {
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        const selectedText = range.extractContents();

        const heading = document.createElement(headingTag);
        heading.appendChild(selectedText);

        range.insertNode(heading); 

        document.getElementById('toolbar').style.display = 'none';
    }


    function showLinkPopup() {
        document.getElementById('linkPopup').style.display = 'block';
        document.getElementById('toolbar').style.display = 'none';
    }

    let selectedText = ""; 
let selectionRange = null;

document.getElementById('contentEditableDiv').addEventListener('mouseup', function () {
    const selection = window.getSelection();
    if (selection.toString().length > 0) {
        selectedText = selection.toString();
        selectionRange = selection.getRangeAt(0);
        const rect = selectionRange.getBoundingClientRect();
        const toolbar = document.getElementById('toolbar');
        toolbar.style.top = rect.top + window.scrollY - toolbar.offsetHeight - 20 + 'px';
        toolbar.style.left = rect.left + window.scrollX + 'px';
        toolbar.style.display = 'block';
    } else {
        selectedText = "";
        selectionRange = null;
        document.getElementById('toolbar').style.display = 'none';
    }
});

function applyLink() {
    const url = document.getElementById('linkUrl').value;
    if (url && selectedText.length > 0 && selectionRange) {
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank'; 
        link.style.fontWeight = 'bold';
        link.style.fontFamily = 'Times, Lora';
        link.style.color = 'green';
        link.style.textDecoration = 'none';

        const textNode = document.createTextNode(selectedText);
        link.appendChild(textNode); 

        selectionRange.deleteContents();
        selectionRange.insertNode(link);

        closeLinkPopup();
        selectedText = "";
        selectionRange = null;
    } else {
        alert('Please enter a valid URL to create a link.');
    }
}

    
    function closeLinkPopup() {
        document.getElementById('linkPopup').style.display = 'none';
    }


</script>


<script>
document.getElementById('contentEditableDiv').addEventListener('paste', function (event) {
    event.preventDefault();

    const pasteHTML = (event.clipboardData || window.clipboardData).getData('text/html') ||
                      (event.clipboardData || window.clipboardData).getData('text/plain');

    console.log("Pasted HTML:", pasteHTML); 

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = pasteHTML;

    const unwantedElements = tempDiv.querySelectorAll('img, iframe, video, audio, hr');
    const tables = tempDiv.querySelectorAll('table');

    unwantedElements.forEach(element => element.remove());
    tables.forEach(table => table.remove());
    
    const boldElements = tempDiv.querySelectorAll('b, strong');
    boldElements.forEach(bold => {
        const textNode = document.createTextNode(bold.innerText);
        bold.parentNode.replaceChild(textNode, bold);
    });

    
    const headings = tempDiv.querySelectorAll('h1, h3, h4, h5, h6');
    headings.forEach(heading => {
        const h2 = document.createElement('h2');
        h2.innerHTML = heading.innerHTML;
        heading.parentNode.replaceChild(h2, heading);
    });

    const allElements = tempDiv.querySelectorAll('*');
    allElements.forEach(element => {
        element.style.fontFamily = 'Times, Lora';
        element.style.color = 'black'; 
        element.style.background = 'none';

        if (element.tagName === 'A') {
            element.style.color = 'green';
            element.style.textDecoration = 'none';
            element.target = '_blank';
            element.style.fontWeight = 'bold';
        }

        if (element.tagName.startsWith('H')) {
            element.style.fontFamily = 'Times, Lora';
            element.style.color = 'black';
            element.style.background = 'none';
        }
    });

    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();

        const fragment = document.createDocumentFragment();
        Array.from(tempDiv.childNodes).forEach(node => fragment.appendChild(node));

        range.insertNode(fragment);

        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
    }
});
</script>


    <input type="hidden" name="content" id="content" required>


    <input type="hidden" name="article_id" value="<?php echo $article_id; ?>"> 

    <button type="submit" class="btn btn-primary">Save Changes</button><span> <a href="profile.php" class="btn-danger">Cancel</a></span>
</form>

<script>
document.querySelector('form').addEventListener('submit', function() {
    const contentDiv = document.getElementById('contentEditableDiv');
    const contentInput = document.getElementById('content');
    
    contentInput.value = contentDiv.innerHTML;
});
</script>
<script>
document.getElementById('image').addEventListener('change', function(event) {
    const fileInput = event.target;
    const currentImage = document.getElementById('currentImage');

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            if (currentImage) {
                currentImage.src = e.target.result;
            } else {
                const newImage = document.createElement('img');
                newImage.src = e.target.result;
                newImage.alt = "New Image Preview";
                newImage.classList.add('image-preview');
                fileInput.parentElement.insertBefore(newImage, fileInput);
            }
        };

        reader.readAsDataURL(file);
    } else if (currentImage) {
        currentImage.remove();
    }
});
document.getElementById('contentEditableDivss').addEventListener('paste', function (event) {
    event.preventDefault();

    const pasteHTML = (event.clipboardData || window.clipboardData).getData('text/html') ||
                      (event.clipboardData || window.clipboardData).getData('text/plain');

    console.log("Pasted HTML:", pasteHTML);

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = pasteHTML;

    const unwantedElements = tempDiv.querySelectorAll('img, iframe, video, audio, hr, table, script, style');
    unwantedElements.forEach(element => element.remove());

    const boldElements = tempDiv.querySelectorAll('b, strong');
    boldElements.forEach(bold => {
        const textNode = document.createTextNode(bold.innerText);
        bold.parentNode.replaceChild(textNode, bold);
    });

    const headings = tempDiv.querySelectorAll('h1, h3, h4, h5, h6');
    headings.forEach(heading => {
        const h2 = document.createElement('h2');
        h2.innerHTML = heading.innerHTML;
        heading.parentNode.replaceChild(h2, heading);
    });

    const allElements = tempDiv.querySelectorAll('*');
    allElements.forEach(element => {
        element.style.fontFamily = 'Times, Lora';
        element.style.color = 'black';
        element.style.background = 'none';

        if (element.tagName === 'A') {
            element.style.color = 'green';
            element.style.textDecoration = 'none';
            element.target = '_blank';
        }

        if (element.tagName === 'H2') {
            element.style.fontFamily = 'Times, Lora';
            element.style.color = 'black';
            element.style.background = 'none';
        }
    });

    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();

        const fragment = document.createDocumentFragment();
        Array.from(tempDiv.childNodes).forEach(node => fragment.appendChild(node));

        range.insertNode(fragment);

        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
    }
});

</script>




    <script>
        function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const imgElement = document.getElementById('currentImage');
        imgElement.src = e.target.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}
    </script>
</body>
</html>