// ALL DATATABLES INSTANCE
// GET RID OF EXTENSIONS IN THE URL COLUMN
var userid = $('#user-id').val();
var cntctid = $('#contactid').val();
// ==================== 
// USER SHOWS PANE(ADD) 
// ==================== 
var usraddshow = $('#user-show-options').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-show-total',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.id",
      visible: false,},
      {data: "data.show_name"},
      {data: "data.show_times"},
      {data: "data.show_date"},
      {data: "data.tickets_left",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var allocated = data;
          }
          else
          {
            var allocated = 0;
          }
          return allocated;
        }
      },
      {data: "data.total_tickets"}
    ],
});
// HIGHLIGHT
$('#user-show-options tbody')
  .on( 'mouseenter', 'td', function () {
      var colIdx = usraddshow.cell(this).index().column;
      $( usraddshow.cells().nodes() ).removeClass( 'highlight' );
      $( usraddshow.column( colIdx ).nodes() ).addClass( 'highlight' );
  });
// SELECT ROW
$('#user-show-options tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#add-show-to-user').addClass('disabled');
    }
    else 
    {
      usraddshow.$('tr.selected').removeClass('selected');
      $('#add-show-to-user').removeClass('disabled');
      $(this).addClass('selected');
    }
});
// ADD SHOW
$('#add-show-to-user').click(function(event) {
  var showid = usraddshow.cell('.selected', 0).data();
  var contactid = $('#dbcontactid').val();
  $.ajax({
    url: '../includes/create-show-per-contact',
    type: 'POST',
    data: {
      contactid: contactid,
      showid: showid,
    },
  })
  .done(function() {
    console.log("success");
    $('#user-show-options-success').removeClass('hidden');
  })
  .fail(function() {
    console.log("error");
    $('#user-show-options-error').removeClass('hidden');
  })
  .always(function() {
    console.log("complete");
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// USER SHOWS PANE(REMOVE) 
// ================ 
var usrrmvshow = $('#user-shows').DataTable({
   // AJAX REQUEST FOR THE PHP
   "ajax": {
     "url":'../includes/get-user-shows.php?id='+ cntctid,
     dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
     },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
   "columns": [
    {data: "data.show_id",
    visible: false,},
    {data: "data.show_name"},
    {data: "data.show_times"},
    {data: "data.show_date"},
    {data: "data.ticket_allocated",
      render: function(data) 
      {
        if(data!=null && data !==undefined)
        {
          var allocated = data;
        }
        else
        {
          var allocated = 0;
        }
        return allocated;
      }
    }
    ],
});
// HIGHLIGHT
$('#user-shows tbody')
 .on( 'mouseenter', 'td', function () {
    var colIdx = usrrmvshow.cell(this).index().column;
    $( usrrmvshow.cells().nodes() ).removeClass( 'highlight' );
    $( usrrmvshow.column( colIdx ).nodes() ).addClass( 'highlight' );
 });
// SELECT ROW
$('#user-shows tbody').on( 'click', 'tr', function () {
   if ($(this).hasClass('selected')) 
   {
      $(this).removeClass('selected');
      $('#remove-user-shows').addClass('disabled');
      $('#add-comp-to-user').addClass('disabled');
   }
   else 
   {
      usrrmvshow.$('tr.selected').removeClass('selected');
      $('#remove-user-shows').removeClass('disabled');
      $('#add-comp-to-user').removeClass('disabled');
      $(this).addClass('selected');
   }
});
// ADD COMP TO CONTACT 
$('#add-comp-modal').click(function(event) {
  var currentallocated = usrrmvshow.cell('.selected', 4).data();
  var allocated = $('#allocated-tickets').val();
  var showid = usrrmvshow.cell('.selected', 0).data();
  var contactid = $('#dbcontactid').val();
  $.ajax({
    url: '../includes/create-user-comps',
    type: 'POST',
    data: {
      contactid: contactid,
      showid: showid,
      alctd_tickets: allocated,
      current_allocated: currentallocated,
    },
  })
  .done(function() {
    $('#user-comp-add-success').removeClass('hidden');
  })
  .fail(function() {
    $('#user-comp-add-error').removeClass('hidden');
  })
  .always(function() {
    // $('#user-comp-add-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// REMOVE CONTACT SHOW
$('#remove-user-shows-modal').click(function(event) {
  var allocated = usrrmvshow.cell('.selected', 4).data();
  var showid = usrrmvshow.cell('.selected', 0).data();
  var contactid = $('#dbcontactid').val();
  $.ajax({
    url: '../includes/delete-user-show',
    type: 'POST',
    data: {
      contactid: contactid,
      showid: showid,
      alctd_tickets: allocated,
    },
  })
  .done(function() {
    $('#user-show-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#user-show-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#user-show-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// ADMIN SHOW PAGE
// ================ 
var admshow = $('#admin-shows').DataTable({
    // AJAX REQUEST FOR THE PHP
    
    "ajax": {
      "url":'../includes/get-show-total.php',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.ticket_id",visible: false,},
      {data: "data.show_id",visible: false,},
      {data: "data.show_name"},
      {data: "data.show_times"},
      {data: "data.show_date"},
      {data: "data.tickets_left",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var allocated = data;
          }
          else
          {
            var allocated = 0;
          }
          return allocated;
        }
      },
      {data: "data.total_tickets"}
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#admin-shows tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = admshow.cell(this).index().column;
    $( admshow.cells().nodes() ).removeClass( 'highlight' );
    $( admshow.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#admin-shows tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-show-btn').addClass('disabled');
      $('#delete-show-btn').addClass('disabled');
    }
    else 
    {
      admshow.$('tr.selected').removeClass('selected');
      $('#edit-show-btn').removeClass('disabled');
      $('#delete-show-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
// EDIT SHOW
$('#edit-show-btn').click(function(event) {
  var id = admshow.cell('.selected', 1).data();
  window.location = "../edit-show.php?id=" + id;
});
// DELETE SHOW
$('#btn-delete-show').click(function() {
  var ticketid = admshow.cell('.selected', 0).data(); 
  var showid = admshow.cell('.selected', 1).data();
  $.ajax({
    url: '../includes/delete-show',
    type: 'POST',
    data: {
      showid: showid,
      ticketid: ticketid,
    },
  })
  .done(function() {
    $('#adm-show-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#adm-show-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#adm-show-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
//==================
// ADMIN USERS PAGE
//==================
var usr = $('#user-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-users',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.id",
      visible: false,},
      {data: "data.name"},
      {data: "data.email"}
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#user-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = usr.cell(this).index().column;
    $( usr.cells().nodes() ).removeClass( 'highlight' );
    $( usr.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#user-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-user-btn').addClass('disabled');
      $('#delete-user-btn').addClass('disabled');
    }
    else 
    {
      usr.$('tr.selected').removeClass('selected');
      $('#edit-user-btn').removeClass('disabled');
      $('#delete-user-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
// EDIT SHOW BTN
$('#edit-user-btn').click(function(event) {
  var id = usr.cell('.selected', 0).data();
  window.location = "../edit-user.php?id=" + id;
});
// DELETE USER
$('#btn-delete-user').click(function() {
  var usrid = usr.cell('.selected', 0).data();
  $.ajax({
    url: '../includes/delete-user',
    type: 'POST',
    data: {
      usrid: usrid,
    },
  })
  .done(function() {
    $('#usr-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#usr-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#usr-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// ROLES PAGE
// ================ 
var roles = $('#roles-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-roles',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.id", visible: false},
      {data: "data.name"},
      {data: "data.slug"}
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#roles-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = roles.cell(this).index().column;
    $( roles.cells().nodes() ).removeClass( 'highlight' );
    $( roles.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#roles-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-roles-btn').addClass('disabled');
      $('#delete-roles-btn').addClass('disabled');
    }
    else 
    {
      roles.$('tr.selected').removeClass('selected');
      $('#edit-roles-btn').removeClass('disabled');
      $('#delete-roles-btn').removeClass('disabled');
      $(this).addClass('selected');
      var id = roles.cell('.selected', 0).data();
    }
});
//EDIT ROLE BTN
$('#edit-roles-btn').click(function(event) {
  var id = roles.cell('.selected', 0).data();
  window.location = "../edit-role.php?id=" + id;
});
//DELETE ROLE BTN
$('#btn-delete-role').click(function() {
  var roleid = roles.cell('.selected', 0).data();
  $.ajax({
    url: '../includes/delete-roles',
    type: 'POST',
    data: {
      roleid: roleid,
    },
  })
  .done(function() {
    $('#role-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#role-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#role-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// PERMISSION PAGE 
// ================
var permission = $('#permission-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-perms',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.id", visible: false},
      {data: "data.name"},
      {data: "data.slug"}
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#permission-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = permission.cell(this).index().column;
    $( permission.cells().nodes() ).removeClass( 'highlight' );
    $( permission.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#permission-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-permission-btn').addClass('disabled');
      $('#delete-permission-btn').addClass('disabled');
    }
    else 
    {
      permission.$('tr.selected').removeClass('selected');
      $('#edit-permission-btn').removeClass('disabled');
      $('#delete-permission-btn').removeClass('disabled');
      $(this).addClass('selected');
      var id = permission.cell('.selected', 0).data();
    }
});
//EDIT PERMISSION BTN
$('#edit-permission-btn').click(function(event) {
  var id = permission.cell('.selected', 0).data();
  window.location = "../edit-permission.php?id=" + id;
});
//DELETE PERMISSION BTN
$('#btn-delete-permission').click(function() {
  var permid = permission.cell('.selected', 0).data();
  $.ajax({
    url: '../includes/delete-permissions',
    type: 'POST',
    data: {
      permid: permid,
    },
  })
  .done(function() {
    $('#permission-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#permission-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#permission-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// PRIORITY PAGE 
// ================
var priority = $('#priority-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-priorities',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "data.usrpr_id", visible: false},
      {data: "data.name"},
      {data: "data.description"}
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#priority-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = priority.cell(this).index().column;
    $( priority.cells().nodes() ).removeClass( 'highlight' );
    $( priority.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#priority-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-priority-btn').addClass('disabled');
      $('#delete-priority-btn').addClass('disabled');
    }
    else 
    {
      priority.$('tr.selected').removeClass('selected');
      $('#edit-priority-btn').removeClass('disabled');
      $('#delete-priority-btn').removeClass('disabled');
      $(this).addClass('selected');
      var id = priority.cell('.selected', 0).data();
    }
});
//EDIT PRIORITY BTN
$('#edit-priority-btn').click(function(event) {
  var id = priority.cell('.selected', 0).data();
  window.location = "../edit-priority.php?id=" + id;
});
//DELETE PRIORITY BTN
$('#btn-delete-priority').click(function() {
  var prtyid = priority.cell('.selected', 0).data();
    $.ajax({
    url: '../includes/delete-priorities',
    type: 'POST',
    data: {
      prtyid: prtyid,
    },
  })
  .done(function() {
    $('#prty-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#prty-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#prty-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// TEMPLATES PAGE 
// ================
var templates = $('#templates-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-email-templates',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "id", visible: false,
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var allocated = data;
          }
          else
          {
            var allocated = "N/A";
          }
          return allocated;
        }
      },
      {data: "settings",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var name = data.title;
          }
          else
          {
            var name = "N/A";
          }
          return name;
        }
      },
      {data: "long_archive_url", visible: false},
      {data: "status", 
        render: function(data)
        {
          var label = data;
          switch(label) 
          {
            case "save":
              var labelbtn = "<span class='label label-danger'>Unscheduled</span>";
            break;
            case "paused":
              var labelbtn = "<span class='label label-default'>" + label + "</span>";
            break;
            case "schedule":
              var labelbtn = "<span class='label label-primary'>Scheduled</span>";
            break;
            case "sending":
              var labelbtn = "<span class='label label-info'>" + label + "</span>";
            break;
            case "sent":
              var labelbtn = "<span class='label label-success'>" + label + "</span>";
            break;
          } 
          return labelbtn;
        }
      },
      {data: "emails_sent"},
      {data: "report_summary", 
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var opened = data.unique_opens;
          }
          else
          {
            var opened = "N/A";
          }
          return opened;
        }
      },
      {data: "report_summary", 
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var clicks = data.clicks;
          }
          else
          {
            var clicks = "N/A";
          }
          return clicks;
        }
      },
      {data: "create_time",
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var nudate = moment(data).format('lll');
          }
          else
          {
            var nudate = "N/A";
          }
          return nudate;
        }
      }
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#templates-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = templates.cell(this).index().column;
    $( templates.cells().nodes() ).removeClass( 'highlight' );
    $( templates.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#templates-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#preview-template').addClass('disabled');
      $('#delete-cmpgn-btn').addClass('disabled');
      $('#edit-cmpgn-btn').addClass('disabled');
    }
    else 
    {
      templates.$('tr.selected').removeClass('selected');
      $('#preview-template').removeClass('disabled');
      $('#delete-cmpgn-btn').removeClass('disabled');
      $('#edit-cmpgn-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
$('#preview-template').click(function(event) {
  var imgsrc = templates.cell('.selected', 2).data();
  $(this).attr("href",imgsrc);
});
//EDIT CAMPAIGN BTN
$('#edit-cmpgn-btn').click(function(event) {
  var cmpgnid = templates.cell('.selected', 0).data();
  window.location = "../edit-campaign.php?id=" + cmpgnid;
});
//DELETE CAMPAIGN BTN
$('#btn-delete-cmpgn').click(function() {
  var cmpgnid = templates.cell('.selected', 0).data();
  $.ajax({
    url: '../includes/delete-campaign',
    type: 'POST',
    data: {
      cmpgnid: cmpgnid,
    },
  })
  .done(function() {
    $('#cmpgn-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#cmpgn-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#cmpgn-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================ 
// SCHEDULE PAGE 
// ================
var scheduled = $('#scheduled-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-scheduled-campaigns',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "id", visible: false,
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var allocated = data;
          }
          else
          {
            var allocated = "N/A";
          }
          return allocated;
        }
      },
      {data: "settings",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var name = data.title;
          }
          else
          {
            var name = "N/A";
          }
          return name;
        }
      },
      {data: "long_archive_url", visible: false},
      {data: "status", 
        render: function(data)
        {
          var label = data;
          switch(label) 
          {
            case "save":
              var labelbtn = "<span class='label label-danger'>Unscheduled</span>";
            break;
            case "paused":
              var labelbtn = "<span class='label label-default'>" + label + "</span>";
            break;
            case "schedule":
              var labelbtn = "<span class='label label-primary'>Scheduled</span>";
            break;
            case "sending":
              var labelbtn = "<span class='label label-info'>" + label + "</span>";
            break;
            case "sent":
              var labelbtn = "<span class='label label-success'>" + label + "</span>";
            break;
          } 
          return labelbtn;
        }
      },
      {data: "emails_sent"},
      {data: "report_summary", 
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var opened = data.unique_opens;
          }
          else
          {
            var opened = "N/A";
          }
          return opened;
        }
      },
      {data: "report_summary", 
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var clicks = data.clicks;
          }
          else
          {
            var clicks = "N/A";
          }
          return clicks;
        }
      },
      {data: "send_time",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var sendate = moment(data).format('lll');
          }
          else
          {
            var sendate = "N/A";
          }
          return sendate;
        } 
      },
      {data: "create_time",
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var nudate = moment(data).format('lll');
          }
          else
          {
            var nudate = "N/A";
          }
          return nudate;
        }
      }
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#scheduled-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = scheduled.cell(this).index().column;
    $( scheduled.cells().nodes() ).removeClass( 'highlight' );
    $( scheduled.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#scheduled-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#unsched-preview-template').addClass('disabled');
      $('#unsched-cmpgn-btn').addClass('disabled');
    }
    else 
    {
      scheduled.$('tr.selected').removeClass('selected');
      $('#unsched-preview-template').removeClass('disabled');
      $('#unsched-cmpgn-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
// PREVIEW SCHEDULED BTN
$('#unsched-preview-template').click(function(event) {
  var imgsrc = scheduled.cell('.selected', 2).data();
  $(this).attr("href",imgsrc);
});
// UNSCHEDULE BTN
$('#btn-unshed-cmpgn').click(function() {
  var cmpgnid = scheduled.cell('.selected', 0).data();
  $.ajax({
    url: '../includes/unschedule-campaign',
    type: 'POST',
    data: {
      cmpgnid: cmpgnid,
    },
  })
  .done(function() {
    $('#unsched-success').removeClass('hidden');
  })
  .fail(function() {
    $('#unsched-error').removeClass('hidden');
  })
  .always(function() {
    $('#unsched-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});
// ================= 
//  CONTACTS PAGE 
// =================
var contacts = $('#contacts-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-contacts',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    select: {
      style: 'multi'
    },
    processing: true,
    deferRender: true,
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "id", visible: false},
      {data: "email_address",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var name = data;
          }
          else
          {
            var name = "N/A";
          }
          return name;
        }
      },
      {data: "list_id", visible: false},
      {data: "merge_fields", 
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var priority = data.PRTY;
          }
          else
          {
            var priority = "N/A";
          }
          return priority;
        }
      },
      {data: "status"},
      {data: "member_rating"},
      {data: "timestamp_opt",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var sendate = moment(data).format('lll');
          }
          else
          {
            var sendate = "N/A";
          }
          return sendate;
        } 
      },
      {data: "last_changed",
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var nudate = moment(data).format('lll');
          }
          else
          {
            var nudate = "N/A";
          }
          return nudate;
        }
      }
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#contacts-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = contacts.cell(this).index().column;
    $( contacts.cells().nodes() ).removeClass( 'highlight' );
    $( contacts.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#contacts-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      $('#edit-contact-btn').addClass('disabled');
      $('#delete-contact-btn').addClass('disabled');
    }
    else 
    {
      // contacts.$('tr.selected').removeClass('selected');
      $('#edit-contact-btn').removeClass('disabled');
      $('#delete-contact-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
// EDIT CONTACT
$('#edit-contact-btn').click(function(event) {
  var contactid = contacts.cell('.selected', 0).data();
  console.log(contactid);
  // window.location = "../edit-contact.php?id=" + contactid;
});
$('#btn-delete-contact').click(function(event) {
  var contactid = contacts.cell('.selected', 0).data();
  console.log(contactid);
  $.ajax({
    url: '../includes/delete-contact',
    type: 'POST',
    data: {
      contactid: contactid,
    },
  })
  .done(function() {
    $('#contact-rmv-success').removeClass('hidden');
  })
  .fail(function() {
    $('#contact-rmv-error').removeClass('hidden');
  })
  .always(function() {
    $('#contact-rmv-msg').addClass('hidden');
    setTimeout(function () { location.reload(true); }, 1000);
  });
});

// ===================
//    GROUPS PAGE
// ===================
var groups = $('#groups-list').DataTable({
    // AJAX REQUEST FOR THE PHP
    "ajax": {
      "url":'../includes/get-groups',
      dataSrc: '' // EMPTY CAUSE IT"S AN ARRAY NOT OBJECT
      },
    dom: 'Bfrtlip',
    processing: true,
    deferRender: true,
    buttons: [
        {
          extend: 'copyHtml5',
          text: '<span><i class="fa fa-files-o"></i>&nbspCOPY</span>',
          titleAttr: 'copy'
        }, {
          extend: 'excelHtml5',
          text: '<span><i class="fa fa-file-excel-o"></i>&nbspEXCEL</span>',
          titleAttr: 'excel'
        }, {
          extend: 'pdfHtml5',
          text: '<span><i class="fa fa-file-pdf-o"></i>&nbspPDF</span>',
          titleAttr: 'pdf'
        }, {
          extend: 'print',
          text: '<span><i class="glyphicon glyphicon-print"></i>&nbspPRINT</span>',
          titleAttr: 'print'
        },
    ],
    "columns": [
      {data: "id", visible: false,
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var allocated = data;
          }
          else
          {
            var allocated = "N/A";
          }
          return allocated;
        }
      },
      {data: "name",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var name = data;
          }
          else
          {
            var name = "N/A";
          }
          return name;
        }
      },
      {data: "list_id", visible: false},
      {data: "member_count"},
      {data: "created_at",
        render: function(data)
        {
          if(data!=null && data !==undefined)
          {
            var sendate = moment(data).format('lll');
          }
          else
          {
            var sendate = "N/A";
          }
          return sendate;
        } 
      },
      {data: "updated_at",
        render: function(data) 
        {
          if(data!=null && data !==undefined)
          {
            var nudate = moment(data).format('lll');
          }
          else
          {
            var nudate = "N/A";
          }
          return nudate;
        }
      }
    ],
});
// HIGHLIGHT ROWS AND COLUMNS
$('#groups-list tbody')
  .on( 'mouseenter', 'td', function () {
    var colIdx = groups.cell(this).index().column;
    $( groups.cells().nodes() ).removeClass( 'highlight' );
    $( groups.column( colIdx ).nodes() ).addClass( 'highlight' );
});
// SELECT ROW
$('#groups-list tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('selected')) 
    {
      $(this).removeClass('selected');
      // $('#edit-contact-btn').addClass('disabled');
      // $('#unsched-cmpgn-btn').addClass('disabled');
    }
    else 
    {
      groups.$('tr.selected').removeClass('selected');
      // $('#edit-contact-btn').removeClass('disabled');
      // $('#unsched-cmpgn-btn').removeClass('disabled');
      $(this).addClass('selected');
    }
});
