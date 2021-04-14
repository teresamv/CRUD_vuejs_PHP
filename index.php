<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP CRUD with Vue.js</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <style>
   .modal-mask {
     position: fixed;
     z-index: 9998;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0, 0, 0, .5);
     display: table;
     transition: opacity .3s ease;
   }

   .modal-wrapper {
     display: table-cell;
     vertical-align: middle;
   }
  </style>
 </head>
 <body>
  <div class="container" id="crudApp">
   <br />
   <h3 align="center">CRUD Operations Mysql using Vue.js with PHP</h3>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">Document List</h3>
      </div>
      <div class="col-md-3" align="right">
        <select class="form-control" v-model="first_category" @change="onChange($event)"/>
          <option value="">Select Category</option>
          <option v-for="data1 in first_category_data" :value="data1.id">{{ data1.category }}</option>
        </select>
      </div>
      <div class="col-md-3" align="right">
       <input type="button" class="btn btn-success btn-xs" @click="openModel" value="Add" />
      </div>
     </div>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <tr>
        <th>Sl No.</th>
        <th>Category</th>
        <th>Document</th>
        <th>Edit</th>
        <th>Delete</th>
       </tr>
       <tr v-for="row in allData">
        <td>{{ row.id }}</td>
        <td>{{ row.category }}</td>
        <td>{{ row.name }}</td>
        <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="fetchData(row.id)">Edit</button></td>
        <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row.id)">Delete</button></td>
       </tr>
      </table>
     </div>
    </div>
   </div>
   <div v-if="myModel">
    <transition name="model">
     <div class="modal-mask">
      <div class="modal-wrapper">
       <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" @click="myModel=false"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ dynamicTitle }}</h4>
         </div>
         <div class="modal-body">
          <div class="form-group">
           <label>Choose Category Name</label>
           
            <select class="form-control" v-model="select_category" />
              <option value="">Select Category</option>
              <option v-for="data1 in category_data" :value="data1.id">{{ data1.category }}</option>
            </select>
          </div>
          <div class="form-group">
           <label>Enter Document Name</label>
           <input type="text" class="form-control" v-model="document" />
          </div>
          
          <br />
          <div align="center">
           <input type="hidden" v-model="hiddenId" />
           <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData" />
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </transition>
   </div>
  </div>
 </body>
</html>

<script>

var application = new Vue({
 el:'#crudApp',
 data:{
  first_category:'',
  first_category_data:'',
  select_category:'',
  category_data:'',
  hiddenId : '',
  allData:'',
  myModel:false,
  actionButton:'Insert',
  dynamicTitle:'Add Document'
 },
 methods:{
  onChange(event) {
    var id = event.target.value;
    axios.post('action.php', {
     action:'getList',
     id:id
    }).then(function(response){
      application.allData = response.data;
    });
        },
  fetchAllData:function(){
   axios.post('action.php', {
    action:'fetchall'
   }).then(function(response){
    var obj = JSON.parse(JSON.stringify(response.data));
    application.allData = obj.data;
    application.first_category_data = obj.data1;
    application.first_category = '';
   });
  },
  
  openModel:function(){
   axios.post("action.php", {
    action:'fetchcategory'
   }).then(function(response){
    application.category_data = response.data;
    application.select_category = '';
   });
   application.document = '';
   application.actionButton = "Insert";
   application.dynamicTitle = "Add Document";
   application.myModel = true;
  },
  submitData:function(){

   if(application.select_category != '' && application.document !='')
   {
    if(application.actionButton == 'Insert')
    {
     axios.post('action.php', {
      action:'insert',
      Category:application.select_category,
      Document:application.document
     }).then(function(response){
      application.myModel = false;
      application.fetchAllData();
      application.select_category = '';
      application.document = '';
      alert(response.data.message);
     });
    }
    if(application.actionButton == 'Update')
    {
     axios.post('action.php', {
      action:'update',
      Category:application.select_category,
      Document:application.document,
      hiddenId : application.hiddenId
     }).then(function(response){
      application.myModel = false;
      application.fetchAllData();
      application.select_category = '';
      application.document = '';
      application.hiddenId = '';
      alert(response.data.message);
     });
    }
   }
   else
   {
    alert("Fill All Field");
   }
  },

  fetchData:function(id){
   axios.post('action.php', {
    action:'fetchSingle',
    id:id
   }).then(function(response){
    
    application.document = '';
    application.hiddenId = '';
    
    var obj = JSON.parse(JSON.stringify(response.data));
    
    application.select_category = obj.data.category_id;
    application.document = obj.data.document;
    application.hiddenId = obj.data.id;

    application.category_data = obj.data1;
    application.myModel = true;
    application.actionButton = 'Update';
    application.dynamicTitle = 'Edit Document';
   });
  },
  deleteData:function(id){
   if(confirm("Are you sure you want to remove this Document?"))
   {
    axios.post('action.php', {
     action:'delete',
     id:id
    }).then(function(response){
     application.fetchAllData();
     alert(response.data.message);
    });
   }
  },
 },
 created:function(){
  this.fetchAllData();
 }
});

</script>