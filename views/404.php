<?php// filepath: C:/wamp64/www/site/views/404.php?>
<?php 
// يمكنك تضمين الهيدر والفوتر لجعلها متناسقة مع الموقع
require_once 'layouts/header.php'; 
?>

<style>
    .error-page-container {
        text-align: center;
        padding: 100px 20px;
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .error-page-container h1 {
        font-size: 8rem;
        margin: 0;
        color: var(--primary-orange);
        line-height: 1;
    }
    .error-page-container h2 {
        font-size: 2rem;
        margin: 10px 0 20px;
        color: var(--heading-color);
    }
    .error-page-container p {
        font-size: 1.1rem;
        color: var(--text-color);
        margin-bottom: 30px;
    }
</style>

<div class="container error-page-container">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>Sorry, the page you are looking for does not exist or has been moved.</p>
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Go to Homepage</a>
</div>

<?php 
require_once 'layouts/footer.php'; 
?>