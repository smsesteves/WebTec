<div id="logout_form">
    <li style="margin-left:6%;"><a href = "index.php?pagina=painel_usuario"><?php  echo $_SESSION['username']; ?></a></li>
    <form class="inline_form" action="logout.php">
        <input class="logout_button" type="submit" value="Logout">
    </form>
</div>