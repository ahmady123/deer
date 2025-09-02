<?php
contentEditableDiv.addEventListener('paste', function (event) {
    event.preventDefault(); // Prevent the default paste behavior

    // Get the HTML content from the clipboard
    const pasteHTML = (event.clipboardData || window.clipboardData).getData('text/html') ||
                      (event.clipboardData || window.clipboardData).getData('text/plain');

    console.log("Pasted HTML:", pasteHTML); // Log the entire pasted HTML content

    // Create a temporary container to parse the HTML
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = pasteHTML;


    // Remove all unwanted elements (images, tables, etc.)
    const unwantedElements = tempDiv.querySelectorAll('img, iframe, video, audio, hr');
    const tables = tempDiv.querySelectorAll('table');

 

    // Remove unwanted elements
    unwantedElements.forEach(element => element.remove());
    tables.forEach(table => table.remove());  // Explicitly remove tables
    const boldElements = tempDiv.querySelectorAll('b, strong');
    boldElements.forEach(bold => {
        const textNode = document.createTextNode(bold.innerText); // Create a text node from the bold element's text
        bold.parentNode.replaceChild(textNode, bold); // Replace the bold element with the text node
    });
   const headings = tempDiv.querySelectorAll('h1, h3, h4, h5, h6');
    headings.forEach(heading => {
        const h2 = document.createElement('h2');
        h2.innerHTML = heading.innerHTML; // Copy the inner HTML
        heading.parentNode.replaceChild(h2, heading); // Replace the heading with H2
    });

    // Apply styles to allowed elements (text, headings, and links)
    const allElements = tempDiv.querySelectorAll('*');
    allElements.forEach(element => {
        element.style.fontFamily = 'Times, Lora'; // Apply font family
        element.style.color = 'black'; // Set text color to black
        element.style.background = 'none'; // Remove background

        // Style links specifically
        if (element.tagName === 'A') {
            element.style.color = 'green'; // Set link color to green
            element.style.textDecoration = 'none'; // Remove underline
            element.target = '_blank'; // Open links in a new tab
        }

        // Apply styles to headings (h1, h2, etc.)
        if (element.tagName.startsWith('H')) {
            element.style.fontFamily = 'Times, Lora'; // Ensure headings have the same font
            element.style.color = 'black';
               element.style.background = 'none';  // Set headings' text color
        }
    });

    // Now insert the cleaned and styled content
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents(); // Remove any selected text

        const fragment = document.createDocumentFragment();
        Array.from(tempDiv.childNodes).forEach(node => fragment.appendChild(node)); // Append nodes in the correct order

        range.insertNode(fragment);

        // Move the caret to the end of the inserted content
        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
    }
});