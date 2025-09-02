document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('mainContent');
    const passwordPopupOverlay = document.getElementById('passwordPopupOverlay');

    // Check if session exists
    fetch('check_session.php')
    .then(response => response.json())
    .then(data => {
        if (data.loggedin) {
            mainContent.style.visibility = 'visible';
        } else {
            passwordPopupOverlay.style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));


    // Handle password submission
    document.getElementById('submitPassword').addEventListener('click', () => {
        const email = document.getElementById('emailInput').value.trim();
        const password = document.getElementById('passwordInput').value.trim();
        console.log(`Email: ${email}, Password: ${password}`); // Debugging
        
        fetch('authenticate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        
        .then(response => {
            // Check if the response is valid JSON
            return response.json();
        })
        .then(data => {
            console.log(data); // Log the data to see what the response looks like
            if (data.success) {
                // Password authentication was successful
                passwordPopupOverlay.style.display = 'none';
                mainContent.style.visibility = 'visible';
            } else {
                alert(data.message); // Display error message if login fails
            }
        })
        .catch(error => console.error('Error:', error));
        
    });
    
});

function showSection(section) {
    document.getElementById('articles').style.display = 'none';
    document.getElementById('comments').style.display = 'none';
    document.getElementById('replies').style.display = 'none';
    document.getElementById(section).style.display = 'block';
    fetchData(section);
}


let deleteSection = '';
let deleteId = '';

function showSection(section) {
    document.getElementById('articles').style.display = 'none';
    document.getElementById('comments').style.display = 'none';
    document.getElementById('replies').style.display = 'none';
    document.getElementById(section).style.display = 'block';
    fetchData(section); // Fetch and display data for the visible section
}

function fetchData(section) {
    fetch(`fetch_data.php?section=${section}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
                console.log(`No ${section} found.`);
                displayItems(section, []);
            } else {
                displayItems(section, data);
            }
        })
        .catch(error => console.error(`Error fetching data: ${error}`));
}

function displayItems(section, data) {
    const listContainer = document.getElementById(section + 'List');

    if (!listContainer) {
        console.error("Element with ID '" + section + "List' not found.");
        return;
    }

    listContainer.innerHTML = '';

    if (data.length === 0) {
        listContainer.innerHTML = '<div>No items found.</div>';
        return;
    }
    data.forEach(item => {
        console.log('Item data:', item);
        let displayText = '';
        let itemId = '';  // Variable to hold the correct ID based on section

        if (section === 'articles') {
            displayText = item.title || 'No Title'; // Articles have a title
            itemId = item.article_id; // ID for articles
        } else if (section === 'comments') {
            displayText = item.comment || 'No Content'; // Comments use 'comment' field
            itemId = item.id; // ID for comments
        } else if (section === 'replies') {
            displayText = item.reply_text || 'No Content'; // Replies use 'reply_text' field
            itemId = item.id; // ID for replies
        }

        listContainer.innerHTML += `
            <div class="list-item">
                <span>${displayText}</span>
                <button onclick="confirmDelete('${section}', ${itemId})">Delete</button>
            </div>
        `;
    });
}

function confirmDelete(section, id) {
    deleteSection = section;
    deleteId = id;
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('confirmPopup').style.display = 'block';

    document.getElementById('confirmDelete').onclick = function() {
        deleteItem(deleteSection, deleteId);
        closePopup();
    };

    document.getElementById('cancelDelete').onclick = function() {
        closePopup();
    };
}

function closePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('confirmPopup').style.display = 'none';
}

function deleteItem(section, id) {
    fetch(`delete_data.php?section=${section}&id=${id}`, { method: 'POST' })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete response:', data);
            if (data.success) {
                fetchData(section); // Refresh list after deletion
            } else {
                console.error(`Failed to delete ${section} item with id ${id}: ${data.message}`);
            }
        })
        .catch(error => console.error(`Error deleting item: ${error}`));
}

function searchItems() {
const query = document.getElementById('searchBar').value.toLowerCase();
const currentSection = document.querySelector('.list[style*="display: block"]').id;
const listContainer = document.getElementById(currentSection + 'List');

if (!listContainer) return;

fetch(`fetch_data.php?section=${currentSection}`)
.then(response => response.json())
.then(data => {
    let filteredData = [];
    if (currentSection === 'articles') {
        filteredData = data.filter(item =>
            (item.title || '').toLowerCase().includes(query) || 
            (item.content || '').toLowerCase().includes(query)
        );
    } else if (currentSection === 'comments') {
        filteredData = data.filter(item =>
            (item.comment || '').toLowerCase().includes(query) || 
            (item.content || '').toLowerCase().includes(query) 
        );
    } else if (currentSection === 'replies') {
        filteredData = data.filter(item =>
            (item.reply_text || '').toLowerCase().includes(query) || 
            (item.content || '').toLowerCase().includes(query) 
        );
    }
    displayItems(currentSection, filteredData);
});
}
window.onload = function() {
    fetch('logout.php', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); 
    })
    .catch(error => console.error('Error:', error));
};
