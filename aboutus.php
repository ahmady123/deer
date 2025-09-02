<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mammal.png">
    <title>About us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ebe7dd;
        }

        .promotion-section {
            position: relative;
      
            overflow: hidden;
        }

        .promotion-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
        }

        .promotion-content {
            position: relative;
            z-index: 1;
        }

        .promotion-content h2 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .promotion-content p {
            font-size: 18px;
            color: #555;
            margin-top: 20px;
            line-height: 1.6;
        }

        .social-buttons {
            margin-top: 30px;
            text-align: center;
        }

        .social-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s, transform 0.2s;
        }

        .social-button.linkedin {
            background-color: #0077b5;
        }

        .social-button.email {
            background-color: #e74c3c;
        }

        .social-button:hover {
            transform: translateY(-2px);
        }

        .social-button i {
            margin-right: 8px;
        }

        .promotion-image {
            text-align: center;
          
        }

        @media (max-width: 768px) {
            .promotion-content, .promotion-image {
                text-align: center;
                flex-direction: column;
            }

            .promotion-image img {
                max-width: 100%;
                height: auto;
            }
        }
        .nav-link{
font-weight: bold;
color: black !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="mammal.png" alt="Logo" style="max-height: 40px;"> Deer
            </a>
         
      
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i>Home</a>
                    </li>
                </ul>
       
        </div>
    </nav>
    
    <section class="promotion-section">
        <div class="container">
            <div class="row">
              <div class="col-lg-1"></div></div>
                <div class="col-lg-10 d-flex justify-content-between align-items-center promotion-content">
                    <div class="promotion-image">
                        <img class="hgf" src="Untitled_design-removebg-preview (1).png" alt="Earbuds Image">
                    </div>
                    <div class="text">
                        <h2>Welcome to Muhammad Ahmad's Platform!</h2>
                        <br>
                        <p>Join our community where users can share their thoughts, experiences, and insights. We believe in fostering open discussions and meaningful exchanges of ideas. Whether you're a casual visitor or looking to collaborate, we've got you covered!</p>
                        
                        <div class="social-buttons ">
                            <p>For any suggestions or inquiries, feel free to contact us. We are also available to create amazing websites for you!</p>
                            <a href="https://www.linkedin.com/in/muhammad-ahmad-86a7172a0/" class="social-button linkedin" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            <a href="mailto:mrahmady90@gmail.com" class="social-button email">
                                <i class="fas fa-envelope"></i>
                                <span>Email</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>