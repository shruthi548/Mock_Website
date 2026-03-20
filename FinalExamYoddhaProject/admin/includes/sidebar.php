<div class="dlabnav" style="background-color: #f9fafb; color: #1e293b; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05); width: 260px; min-height: 100vh;">
    <div class="dlabnav-scroll" style="padding: 20px 0;">

        <!-- Menu List -->
        <ul class="metismenu" id="menu" style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">
                <a href="index.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #1e293b; text-decoration: none; border-radius: 8px; transition: background 0.3s;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='transparent'">
                    <i class="flaticon-025-dashboard" style="font-size: 18px; color: #0d47a1;"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li style="margin-bottom: 10px;">
                <a href="students.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #1e293b; text-decoration: none; border-radius: 8px;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='transparent'">
                    <i class="flaticon-086-star" style="font-size: 18px; color: #0d47a1;"></i>
                    <span class="nav-text">Manage Users</span>
                </a>
            </li>

            <li style="margin-bottom: 10px;">
                <a href="manage_educators.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #1e293b; text-decoration: none; border-radius: 8px;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='transparent'">
                    <i class="flaticon-041-graph" style="font-size: 18px; color: #0d47a1;"></i>
                    <span class="nav-text">Educators</span>
                </a>
            </li>

            <li style="margin-bottom: 10px;">
                <a href="manage_test_names.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #1e293b; text-decoration: none; border-radius: 8px;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='transparent'">
                    <i class="flaticon-050-info" style="font-size: 18px; color: #0d47a1;"></i>
                    <span class="nav-text">Test Name</span>
                </a>
            </li>

            <li style="margin-bottom: 10px;">
                <a href="manage_exams.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #1e293b; text-decoration: none; border-radius: 8px;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='transparent'">
                    <i class="flaticon-050-info" style="font-size: 18px; color: #0d47a1;"></i>
                    <span class="nav-text">Tests</span>
                </a>
            </li>
        </ul>

        <!-- Profile Info -->
        <div class="dropdown header-profile2" style="border-top: 1px solid #e2e8f0; margin-top: 30px;">
            <div class="header-info2 text-center">
                <img src="public/assets/images/profile/pic1.jpg" alt="Admin Profile" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 10px; border: 2px solid #0d47a1;">
                <div class="sidebar-info">
                    <h5 class="font-w500 mb-1" style="font-size: 16px; color: #0f172a;"><?php echo $_SESSION['a_name'];?></h5>
                </div>
                <div>
                    <a href="controller/logout.php" class="btn btn-sm" style="margin-top: 10px; background-color: #ff6f00; color: white; padding: 6px 16px; border-radius: 5px; font-size: 14px; text-decoration: none;" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="copyright" style="padding: 20px; font-size: 13px; border-top: 1px solid #e2e8f0;">
            <p class="text-center" style="margin: 0; color: #64748b;"><strong>Admin Dashboard</strong> © 2025</p>
            <p class="text-center" style="margin-top: 5px; color: #94a3b8;">Made with ❤️ by <span style="color: #ff6f00;">ExamYoddha</span></p>
        </div>

    </div>
</div>
