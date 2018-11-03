window.onload = function () {
    
    var users_table=new TableView("users_table","http://localhost/testview/TestView.php?action=view_table");
    
    users_table.init();
 }