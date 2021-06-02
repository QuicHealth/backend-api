$(document).ready(function() {
    $('.selectpicker').selectpicker();
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

    $('.iq-menu-bt').on('click', ()=>{
        $('.wrapper-menu').toggleClass('open');
        $('body').toggleClass('sidebar-main');
    });
});
