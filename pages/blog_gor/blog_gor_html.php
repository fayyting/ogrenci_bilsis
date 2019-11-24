<?php 
function echo_blog_gor_page(Blog_gorController $controller){ 
    $blog = $controller->blog;
    $controller->printMessages();
    ?> 
    <div class="container-fluid text-center content">  
        <h1><?php echo $blog->baslik; ?></h1>
        <?php echo $blog->yayinlanma_tarihi; ?>
        <br>
        <label><?php 
        $user_id = $blog->yazar;
        $user = User::getUserById($user_id);
        echo $user->NAME." ".$user->SURNAME; ?></label>
        <p><?php echo htmlspecialchars_decode($blog->icerik);?> </p>
        <h3> Yorumlar</h3>
        <?php 
        foreach ($controller->yorumlar as $yorum){
            echo $yorum->yorum_metni."<br>";
            $user_id = $yorum->yazar;
            $user = User::getUserById($user_id);
            echo $user->NAME." ".$user->SURNAME."<br>";
            echo "<br><br>";

        }
        ?>
        <form method="POST">
            <label> YORUM YAP </label>
            <input type="text" class="form-control" name="comment"/>
            <input type="submit" name="ekle" value="yorumu gönder" class="form-control "/>
        </form>
    </div>
<?php }  