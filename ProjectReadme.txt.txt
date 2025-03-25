Web_Project-Group 3

****Description of Files****

index.php – Home page that introduces the Student Course Hub with navigation to student, staff, and admin login pages.
admin_dashboard.php – Main dashboard for the admin, providing quick access to manage programs, modules, view interested students, and export data.
staff_dashboard.php – Main dashboard for staff users showing assigned modules, related programmes, profile, and tasks.
student_dashboard.php – Displays available programs for students to view and register interest in.
programme_details.php – Displays individual programme information with related modules and a form to register or withdraw interest.
manage_programs.php – Admin interface to view, add, edit, and delete degree programs.
add_programme.php – Logic to add a new programme to the database.
edit_programme.php – Logic to update existing programme details.
delete_programme.php – Deletes a programme from the database.
update_programme.php – AJAX handler for updating programmes inline.
manage_modules.php – Admin interface to manage modules and assign staff to them.
add_module.php – Logic to add a new module.
edit_module.php – Page for editing module information.
delete_module.php – Deletes a module from the database.
update_status.php – Used for updating module or programme status dynamically.
view_interested_students.php – Shows a searchable and exportable table of students who registered interest.
register_interest.php – Backend form handling for registering or removing student interest.
change_password.php – Used by staff to change password securely or reset via token.
edit_profile.php – Allows staff to edit their profile details like bio, phone, department, and image.
export_data.php – Handles exporting of students, modules, or programme data to CSV.
get_program.php – AJAX handler to fetch programme info.
get_staff_list.php – Used to fetch staff dropdown list dynamically.
hash_password.php – Utility for hashing a password (e.g., for admin/staff insert).
login.php – Login page for staff/admin users.
logout.php – Clears session and redirects to login page.
connect.php – Central database connection file used across all scripts.

/uploads/ – Contains images for staff, modules, and programme backgrounds.
admin_styles.css – Styling for admin interface.
interested_styles.css – Custom design for the "View Interested Students" page.
style.css – Main website styling shared by public-facing components.

****Workflow Description****

The Student Course Hub is designed for a university to market undergraduate and postgraduate programs.
- Students can explore programmes and register their interest.
- Staff members can view their assigned modules and linked programmes, edit their profile, and change passwords.
- Admins manage programmes, modules, view interested students, and export data.
The data is stored in a MySQL database (student_course_hub.sql), and all interactions are processed via PHP, AJAX, HTML, CSS, and JavaScript.