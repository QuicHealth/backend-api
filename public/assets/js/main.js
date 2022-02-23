$(document).ready(function() {
<<<<<<< HEAD

    $('.select-courses').select2({
       width:'resolve'
    });
    $('.select-courses1').select2({
        width:'resolve'
    });
    $('.select-courses2').select2({
        width:'resolve'
    });

=======
    $('.selectpicker').selectpicker();
>>>>>>> 134f3877701c5c9f4b7455c81d5f344eee2c9197
    $('#datatable').DataTable();

    $('#datatableF').DataTable({
        "bSort" : false
    });

    var filterStudent=$('#students').DataTable({
        dom: 'lrtip'  //i remove 'f' because it shows the search
    });
    $('#filterStudent').on('change', function(){
        filterStudent.search(this.value).draw();
    });

    var resultTable=$('#filtertable').DataTable( {
        // dom: 'lrtip'
    });
    $('#filterSelect').on('change', function(){
        resultTable.search(this.value).draw();
        // alert('hello')
    });
<<<<<<< HEAD
=======

    $('.iq-menu-bt').on('click', ()=>{
        $('.wrapper-menu').toggleClass('open');
        $('body').toggleClass('sidebar-main');
    });
>>>>>>> 134f3877701c5c9f4b7455c81d5f344eee2c9197
});
