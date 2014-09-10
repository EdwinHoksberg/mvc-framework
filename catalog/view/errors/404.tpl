<div id="content">
    <h1>404 - Page not found</h1>
    <hr>
    <p>
        <?php if ($type == 'view'): ?>
            Could not find view <i><?php echo $page; ?></i>.
        <?php elseif ($type == 'controller'): ?>
            Could not find page <i><?php echo $controller; ?>::<?php echo $action; ?></i>.
        <?php endif; ?>
        <br /><br />
        <a href="<?php echo HTTP_SERVER; ?>">Click here to return to the homepage.</a>
    </p>
</div>
