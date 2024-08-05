<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <base href="<?= base_url() ?>">
   <meta property="og:type" content="object" />
   <meta property="og:site_name" content="<?= _project_name_for_web_title_ ?>" />
   <title><?= $meta_title ?></title>
   <meta name="description" content="<?= $meta_description ?>">
   <meta name="keywords" content="<?= $meta_keywords ?>">
   <link rel="shortcut icon" type="image/x-icon" href="<?= IMAGE ?>logo-2.png">
   <link rel="stylesheet" href="<?php echo CSS ?>main.css">
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <style>
      body {
         background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
         min-height: 100vh;
         display: flex;
         flex-direction: column;
         font-family: 'Arial', sans-serif;
      }


      .navbar-brand img {
         height: 90px;
      }

      .content-wrapper {
         flex: 1;
         display: flex;
         align-items: center;
      }

      .container {
         padding: 2rem 0;
      }

      .card {
         border: none;
         border-radius: 1rem;
         box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
         overflow: hidden;
      }

      .card-header {
         background: linear-gradient(to right, #4a00e0, #8e2de2);
         color: white;
         font-weight: bold;
         text-transform: uppercase;
         letter-spacing: 0.1em;
         border-bottom: none;
         padding: 1.5rem;
      }

      .form-control {
         border-radius: 0.5rem;
         padding: 0.75rem 1rem;
      }

      .btn-primary {
         background: linear-gradient(to right, #4a00e0, #8e2de2);
         border: none;
         border-radius: 0.5rem;
         padding: 0.75rem 2rem;
         font-weight: bold;
         letter-spacing: 0.05em;
         transition: all 0.3s ease;
      }

      .btn-primary:hover {
         transform: translateY(-3px);
         box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
      }

      .footer {
         background: linear-gradient(to right, #4a00e0, #8e2de2);
         color: white;
         padding: 2rem 0;
         margin-top: auto;
      }

      .footer a {
         color: #ffffff;
         text-decoration: none;
         transition: all 0.3s ease;
      }

      .footer a:hover {
         color: #84fab0;
      }

      .footer-icons {
         font-size: 1.5rem;
      }

      .footer-icons a {
         margin: 0 10px;
      }

      .nav-con.container {
         padding: 0;
      }

      a.nav-link {
         font-size: 17px;
         font-weight: 700;
         background: #ffebc5;
         padding: 9px 17px 9px 20px;
         border-radius: 2px;
         color: black !important;
      }
   </style>
</head>

<body>
   <section>
      <div class="nav-con container">
         <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="<?= MAINSITE ?>">
               <div>
                  <img src="<?= IMAGE ?>logo-2.png" alt="">
               </div>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
               aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                     <a class="nav-link" href="<?= MAINSITE ?>"><i class="fas fa-home mr-2"></i>Home</a>
                  </li>

               </ul>
            </div>
         </nav>
      </div>
   </section>