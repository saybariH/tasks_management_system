<!-- <nav class="side-bar">
			<!-- <div class="user-p">
				<img src="img/user.png">
				<h4>@<?=$_SESSION['username']?></h4>
			</div> -->
		
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle" id="header-toggle"  > <i  class="fa fa-bars" ></i> </div>
		<div class="header_user">
        <div class="header_img">
            <img src="img/user.png" alt="User Image">
        </div>
        <h5 class="user_name"><?=$_SESSION['username']?></h5>
		
    </div>
    </header>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");:root{--header-height: 3rem;--nav-width: 68px;--first-color: #4723D9;--first-color-light: #AFA5D9;--white-color: #F7F6FB;--body-font: 'Nunito', sans-serif;--normal-font-size: 1rem;--z-fixed: 100}*,::before,::after{box-sizing: border-box}body{position: relative;margin: var(--header-height) 0 0 0;padding: 0 1rem;font-family: var(--body-font);font-size: var(--normal-font-size);transition: .5s}a{text-decoration: none} .header{width: 100%;height: var(--header-height);position: fixed;top: 0;left: 0;display: flex;align-items: center;justify-content: space-between;padding: 0 1rem;background-color: var(--white-color);z-index: var(--z-fixed);transition: .5s}.header_toggle{color: var(--first-color);font-size: 1.5rem;cursor: pointer}.header_img{width: 35px;height: 35px;display: flex;justify-content: center;border-radius: 50%;overflow: hidden}.header_img img{width: 40px}.l-navbar{position: fixed;top: 0;left: -30%;width: var(--nav-width);height: 100vh;background-color: var(--first-color);padding: .5rem 1rem 0 0;transition: .5s;z-index: var(--z-fixed)}.nav{height: 100%;display: flex;flex-direction: column;justify-content: space-between;overflow: hidden}.nav_logo, .nav_link{display: grid;grid-template-columns: max-content max-content;align-items: center;column-gap: 1rem;padding: .5rem 0 .5rem 1.5rem}.nav_logo{margin-bottom: 2rem}.nav_logo-icon{font-size: 1.25rem;color: var(--white-color)}.nav_logo-name{color: var(--white-color);font-weight: 700}.nav_link{position: relative;color: var(--first-color-light);margin-bottom: 1.5rem;transition: .3s}.nav_link:hover{color: var(--white-color)}.nav_icon{font-size: 1.25rem}.show{left: 0}.body-pd{padding-left: calc(var(--nav-width) + 1rem)}.active{color: var(--white-color)}.active::before{content: '';position: absolute;left: 0;width: 2px;height: 32px;background-color: var(--white-color)}.height-100{height:100vh}@media screen and (min-width: 768px){body{margin: calc(var(--header-height) + 1rem) 0 0 0;padding-left: calc(var(--nav-width) + 2rem)}.header{height: calc(var(--header-height) + 1rem);padding: 0 2rem 0 calc(var(--nav-width) + 2rem)}.header_img{width: 40px;height: 40px}.header_img img{width: 45px}.l-navbar{left: 0;padding: 1rem 1rem 0 0}.show{width: calc(var(--nav-width) + 156px)}.body-pd{padding-left: calc(var(--nav-width) + 188px)} .header_toggle i {
        color: var(--first-color); /* Applique la même couleur que .nav_link */
    }}
	</style>
	<style>
		.header_user {
    display: flex;
    align-items: center; /* Center vertically */
    gap: 10px; /* Space between the image and the name */
}

.header_img {
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%; /* Circle image */
    overflow: hidden;
}

.header_img img {
    width: 100%; /* The image will fill the container */
    height: auto; /* Maintain aspect ratio */
}

.user_name {
    font-size: 1rem;
    color: var(--first-color);
    font-weight: 600; /* Bold username */
    white-space: nowrap; /* Prevent text from wrapping */
}
	</style>
	<?php 

if($_SESSION['role'] == "employee"){
	?>
	
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Task <b>Manager</b></span> </a>
                <div class="nav_list"> 
					<a href="index.php" class="nav_link "> 
					<i class="fa fa-tasks" aria-hidden="true"></i> <span class="nav_name">Projects</span> </a> 
					<a href="my_task.php" class="nav_link"> <i class="fa fa-tasks" aria-hidden="true"></i> <span class="nav_name">My Tasks</span> </a> 
					<a href="profile.php" class="nav_link"> <i class="fa fa-user" aria-hidden="true"></i><span>Profile</span> </a> 
					<a href="messages.php" class="nav_link"> <i class="fa fa-briefcase" aria-hidden="true"></i> <span class="nav_name">messages</span> </a> 
					<a href="logout.php" class="nav_link"> <i class="fa fa-sign-out" aria-hidden="true"></i><span>Logout</span> </a> </div>
            </div> 
			
        </nav>
    </div>
	<?php }else { ?>
		<div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Task <b>Manager</b></span> </a>
                <div class="nav_list"> 
					<a href="index.php" class="nav_link "> 
					<i class="fa fa-tachometer" aria-hidden="true"></i> <span class="nav_name">Dashboard</span> </a> 
					<a href="user.php" class="nav_link"> <i class="fa fa-user-plus" aria-hidden="true"></i> <span class="nav_name">Manage Users</span> </a> 
					<a href="teams.php" class="nav_link"> <i class="fa fa-users" aria-hidden="true"></i> <span class="nav_name">Manage Teams</span> </a> 
					<a href="projects.php" class="nav_link"> <i class="fa fa-briefcase" aria-hidden="true"></i> <span class="nav_name">Manage Project</span> </a> 
					<a href="tasks.php" class="nav_link"> <i class="fa fa-briefcase" aria-hidden="true"></i> <span class="nav_name">Manage tasks</span> </a> 
                    <a href="logout.php" class="nav_link"> <i class="fa fa-sign-out" aria-hidden="true"></i><span>Logout</span> </a> </div>
            </div> 
			
        </nav>
    </div>
	<?php } ?>
	<script>
document.addEventListener("DOMContentLoaded", function(event) {
   
   const showNavbar = (toggleId, navId, bodyId, headerId) =>{
      const toggle = document.getElementById(toggleId),
      nav = document.getElementById(navId),
      bodypd = document.getElementById(bodyId),
      headerpd = document.getElementById(headerId)
      
      // Validate that all variables exist
      if(toggle && nav && bodypd && headerpd){
         toggle.addEventListener('click', ()=> {
            // show navbar
            nav.classList.toggle('show')
            // change icon
            toggle.classList.toggle('bx-x')
            // add padding to body
            bodypd.classList.toggle('body-pd')
            // add padding to header
            headerpd.classList.toggle('body-pd')
         })
      }
   }
   
   showNavbar('header-toggle','nav-bar','body-pd','header')
   
   /*===== LINK ACTIVE BASED ON CLICK =====*/
   const linkColor = document.querySelectorAll('.nav_link')
   
   function colorLink(){
      if(linkColor){
         linkColor.forEach(l => l.classList.remove('active'))
         this.classList.add('active')
      }
   }
   
   linkColor.forEach(l => l.addEventListener('click', colorLink))
   
   /*===== LINK ACTIVE BASED ON CURRENT URL =====*/
   const currentPath = window.location.pathname.split('/').pop(); // Get the current page name (e.g., "profile.php")
   
   linkColor.forEach(link => {
      const linkPath = link.getAttribute('href'); // Get the href of the link (e.g., "profile.php")
      if(currentPath === linkPath) {
         link.classList.add('active'); // Apply active class to the matching link
      }
   });
});
</script>
 