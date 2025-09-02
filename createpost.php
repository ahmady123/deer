<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Article</title>
    <link rel="icon" href="mammal.png">
    <link rel="stylesheet" href="post.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .hidden {
            display: none;
        }
        #toolbar {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: 1px solid #ccc;
            padding: 5px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        #linkInputContainer {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 2000;
        }
        #articleContent {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 500px;
            overflow-wrap: break-word;
            white-space: pre-wrap;
            font-family: times;
        }
        .upload-container {
            margin-bottom: 15px;
        }
    
        .toolbar-button {
            margin: 0 5px;
        }
        #imagePreview {
        width: 200px;
        height: 150px; 
        
        border: 1px solid #ccc; 
    }
    .btn-danger{
    background-color:rgb(255, 0, 0);
    color: white;
    border: none;
    padding: 8px 15px;
 
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    text-decoration: none !important;
}

    </style>
</head>
<body class="container my-4">

    <h1>Upload Your Article</h1>
    <form id="articleForm" action="upload_article.php" method="POST" enctype="multipart/form-data">
        <div class="upload-container">
            <input type="file" id="imageInput" name="article_image" accept="image/*" style="display: none;" />
            <button type="button" id="uploadButton" class="btn btn-secondary">Upload Image</button>
            <img id="imagePreview" class="hidden image-preview" alt="Image preview" />
            <span id="imageError" class="text-danger" style="display: none;">Please upload an image.</span>
        </div>

        <div class="form-group">
    <label for="articleTitle">Title:</label>
    <input type="text" id="articleTitle" name="article_title" class="form-control"  maxlength="75">
    <span id="titleError" class="text-danger" style="display: none;">Title cannot be empty.</span>
</div><br>


        <div class="form-group">
            <p>Select any text to make it heading or add a link.Or double tap on the text in mobile</p>
            <div style="position: relative;">
                <div id="articleContent" contenteditable="true"></div>
                <div id="toolbar" class="hidden">
                    <button type="button" class="toolbar-button btn btn-outline-secondary btn-sm" onclick="formatText('h2')">H2</button>
                    <button type="button" class="toolbar-button btn btn-outline-secondary btn-sm" onclick="formatText('h3')">H3</button>
                    <button type="button" class="toolbar-button btn btn-outline-secondary btn-sm" onclick="formatText('h4')">H4</button>
                    <button type="button" class="toolbar-button btn btn-outline-secondary btn-sm" onclick="showLinkInput()">Link</button>
                </div>
            </div>
            <input type="hidden" id="articleContentHidden" name="article_content">
            <span id="contentError" class="text-danger" style="display: none;">Content cannot be empty.</span>
        </div><br>

        <button type="submit" id="submitArticle" class="btn btn-primary">Submit Article</button><span><a href="index.php" class="btn-danger">Cancel</a></span>
    </form>


    <div id="linkInputContainer">
        <label for="linkInput">Enter URL:</label>
        <input type="text" id="linkInput"value="http://" class="form-control">
        <button type="button" onclick="applyLink()" class="btn btn-primary btn-sm mt-2">Apply</button>
        <button type="button" onclick="hideLinkInput()" class="btn btn-secondary btn-sm mt-2">Cancel</button>
    </div>
    <script>
        articleForm.addEventListener('submit', function (e) {
    let isValid = true;

    // Validate title
    const titleInput = document.getElementById('articleTitle');
    const titleValue = titleInput.value.trim();
    const titleError = document.getElementById('titleError');
    if (!titleValue) {
        titleError.style.display = 'block'; // Show error
        isValid = false;
    } else {
        titleError.style.display = 'none'; // Hide error
    }

    // Validate content
    const contentValue = contentEditableDiv.innerHTML.trim();
    const contentError = document.getElementById('contentError');
    if (!contentValue || contentValue === '<br>') {
        contentError.style.display = 'block'; // Show error
        isValid = false;
    } else {
        contentError.style.display = 'none'; // Hide error
        document.getElementById('articleContentHidden').value = contentValue;
    }

    // Prevent submission if validation fails
    if (!isValid) {
        e.preventDefault();
    }
});

        articleForm.addEventListener('submit', function (e) {
    let isValid = true;


    const contentValue = contentEditableDiv.innerHTML.trim();
    const contentError = document.getElementById('contentError');
    if (!contentValue || contentValue === '<br>') {
        contentError.style.display = 'block';
        isValid = false;
    } else {
        contentError.style.display = 'none';
        document.getElementById('articleContentHidden').value = contentValue;
    }


    if (!isValid) {
        e.preventDefault();
    }
});

    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadButton = document.getElementById('uploadButton');
        const contentEditableDiv = document.getElementById('articleContent');
        const toolbar = document.getElementById('toolbar');
        const articleForm = document.getElementById('articleForm');
        const linkInputContainer = document.getElementById('linkInputContainer');
        const linkInput = document.getElementById('linkInput');
        let selectedRange = null;



        articleForm.addEventListener('submit', function (e) {
            const contentValue = contentEditableDiv.innerHTML;
            document.getElementById('articleContentHidden').value = contentValue;

            const imageFile = imageInput.files[0];
            const imageError = document.getElementById('imageError');

            imageError.style.display = 'none';

            if (!imageFile) {
                imageError.style.display = 'block'; 
                e.preventDefault(); 
            }

            if (!contentValue.trim()) {
               
                e.preventDefault();
            }
        });
    });
</script>
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadButton = document.getElementById('uploadButton');
        const contentEditableDiv = document.getElementById('articleContent');
        const toolbar = document.getElementById('toolbar');
        const articleForm = document.getElementById('articleForm');
        const linkInputContainer = document.getElementById('linkInputContainer');
        const linkInput = document.getElementById('linkInput');
        let selectedRange = null;

        uploadButton.addEventListener('click', function () {
            imageInput.click();
        });

        imageInput.addEventListener('change', function () {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        contentEditableDiv.addEventListener('paste', function (event) {
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



        contentEditableDiv.addEventListener('mouseup', function () {
            const selection = window.getSelection();
            if (selection.rangeCount > 0 && selection.toString().length > 0) {
                toolbar.style.display = 'block';
                selectedRange = selection.getRangeAt(0);
            } else {
                toolbar.style.display = 'none';
            }
        });

        articleForm.addEventListener('submit', function (e) {
            const contentValue = contentEditableDiv.innerHTML;
            document.getElementById('articleContentHidden').value = contentValue;

            if (!contentValue.trim()) {
             
                e.preventDefault();
            }
        });

        function formatText(tag) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                const newElement = document.createElement(tag);
                newElement.textContent = range.toString();
                range.deleteContents();
                range.insertNode(newElement);
                toolbar.style.display = 'none';
            }
        }

        function showLinkInput() {
            if (selectedRange) {
                linkInputContainer.style.display = 'block';
                linkInput.focus();
            }
        }

        function hideLinkInput() {
            linkInputContainer.style.display = 'none';
            linkInput.value = '';
        }

        function applyLink() {
    const url = linkInput.value.trim();
    if (url && selectedRange) {
        const link = document.createElement('a');
        link.href = url;
        link.textContent = selectedRange.toString();
        link.target = '_blank';
        link.style.color = 'green';
        link.style.fontFamily = 'Times, Lora';
        link.style.textDecoration = 'none';
        link.style.fontWeight = 'bold';
        selectedRange.deleteContents();
        selectedRange.insertNode(link);
    }
    hideLinkInput();
}
    </script>
</body>
</html>
