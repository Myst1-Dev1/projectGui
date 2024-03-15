<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>
<body>
    <header class="container">
        <h1 class="mt-3">
            <?php
                if(function_exists('the_custom_logo')) {
                    the_custom_logo();
                }
            ?>
        </h1>
        <nav class="mt-5 navbar navbar-expand-lg navbar-dark" >
        
        <button class="mt-3 navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navigation" class="collapse navbar-collapse flex-column" >			    
            <?php
                wp_nav_menu(
                    array(
                        'menu' => 'primary',
                        'container' => '',
                        'theme_location' => 'primary',
                        'items_wrap' => '<ul id="" 
                            class="navbar-nav">%3$s</ul>'
                    )
                );
            ?>
        </div>
    </nav>
    </header>  