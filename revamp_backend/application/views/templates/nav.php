
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../index.php">CMAP</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php echo (isset($menu)&&$menu=="users")?'active':''; ?>">
				<a href="../users_list.php">Users</a>
			</li>            
            <li class="dropdown <?php echo (isset($menu)&&$menu=="rooms")?'active':''; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Rooms<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a href="../room_insert.php">Add New</a></li>
                <li><a href="../room_list.php">View Rooms</a></li>                  
          </ul>
           </li>
            <li class="<?php echo (isset($menu)&&$menu=="booking")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Booking<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a href="../booking.php">Add New</a></li>
                <li><a href="../booking_list.php">View Booking</a></li>                  
          	  </ul>
          	</li>  
           
           <li class="<?php echo (isset($menu)&&$menu=="calendar")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Calendar<b class="caret"></b></a>
            <ul class="dropdown-menu">
            	<li><a href="../calendar.php" target="new">Old Calendar</a></li>
              	<li><a href="../calendar/calendar.php" target="_blank">New Calendar</a></li>

            </ul>   
           </li>  
              
      <!--     <li class="<?php echo (isset($menu)&&$menu=="block")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Block<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a href="block.php">Add New</a></li>
                <li><a href="block_list.php">View block dates</a></li>                
          </ul></li>   -->   
           <li class="<?php echo (isset($menu)&&$menu=="staff")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Staff<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a href="../staff_add.php">Add Staff</a></li>
                <li><a href="../staff_list.php">View Staff</a></li>                  
          </ul></li> 
          <li class="<?php echo (isset($menu)&&$menu=="page")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Page<b class="caret"></b></a>
              <ul class="dropdown-menu">              	
                <li><a href="../page/index.php?/page/">View Pages</a></li>                  
				<li><a href="../page/index.php?/company/">View Company</a></li>
              </ul>
          </li>            
           <li class="<?php echo (isset($menu)&&$menu=="system")?'active':''; ?>"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">System<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a href="../email_edit.php">Email Setting</a></li>                  
              	
          	  </ul>
          	</li>  
          
          <!--<li class="<?php echo (isset($menu)&&$menu=="mail")?'active':''; ?>"><a href="mail.php">Mail Settings</a></li>-->
           </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown <?php echo (isset($menu)&&$menu=="admin")?'active':''; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="../profile.php">Profile</a></li>
                <li><a href="../logout.php">Log Out</a></li>               
          </ul>
           </li>           
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>