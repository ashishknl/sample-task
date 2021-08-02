<template>
    <div class="row dragable">
		<div class="col-md-4 dragable__scroll" v-for="(item, index1) in allContents">   
            <section class="list">
                <header>{{splitColumn(index1)}}</header>
                <draggable class="list__drag-area"  :list="item" :options="{animation:200, group:'status'}" :element="'article'" @add="onAdd($event, true,index1)"  @change="update()">
                    <article class="list__drag-area__card" v-for="(task, index) in item" :key="task.id" :data-id="task.id">
                        <header v-on:click="show(task.desc,task.title,task.id)">
                            {{ task.title }}
                        </header>
						<button @click="onRemove(task.id)" :data-id="task.id" >Delete</button>
                    </article>
                </draggable>  
				<div class="add-new-task">
                <input class="add-new-task__input" placeholder="Enter task name" type="text" :id="index1">
				<a class="add-new-task__input__link" href="javascript:void(0)" v-on:click="addTask(true,index1)">Add Task</a>
				</div>
            </section>
        </div>
		<div class="col-md-4 dragable__scroll">   
            <section class="list">
	<div class="add-new-task">
                <input class="add-new-task__input" type="text" placeholder="Enter column name" id="add_column_value">
				<a class="add-new-task__input__link" href="javascript:void(0)" v-on:click="addColumn()">Add Column</a>
				</div>
            </section>
        </div>
		<modal name="card_modal">
		<input type="hidden" id="card_id">
		<div class="card-modal-container">
			<input class="card-modal-container__input" type="text" id="card_title" placeholder="Enter task title">
		</div>
        		<div class="card-modal-container__description">
			<textarea id="card_description" placeholder="Enter task description"  rows="10" cols="78"></textarea>
		</div>
 <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="hide()">Close</button>
		<button type="button" class="btn btn-secondary" @click="saveCard()">Save Changes</button>
      </div>
    </modal>
    </div>
</template>

<script>

    import draggable from 'vuedraggable'
	import VModal from 'vue-js-modal'
	Vue.use(VModal)

   export  default {
        components: {
            draggable
        },
        props: ['allContents'],
        data() {
            return {
                allContentsNew: this.allContents
            }
        },
        methods: {
            onAdd(event, status,column_id) {
                let id = event.item.getAttribute('data-id');
                axios.patch('/demos/tasks/' + id, {
                    status: status,
					column_id:parseInt(column_id)
                }).then((response) => {
                    console.log(response.data);
                }).catch((error) => {
                    console.log(error);
                })
            },
            update:function() {
			var parsedobj = JSON.parse(JSON.stringify(this.allContentsNew))
			let tasks=objLength(parsedobj);
			tasks.map((task, index) => {
                    task.order = index + 1;
                });

                axios.put('/demos/tasks/updateAll', {
                    tasks: tasks
                }).then((response) => {
                    console.log(response.data);
                }).catch((error) => {
                    console.log(error);
                })
            },
			onRemove:function(remove_id) {
				var id=remove_id;
                axios.delete('/demos/tasks/' + id, {
                    status: status
                }).then((response) => {
                    console.log(response.data);
					window.location.reload();
                }).catch((error) => {
                    console.log(error);
                })
            },
			addTask:function(status,value)
			{
			var title=document.getElementById(value).value;
			var split_column = value.split("_");
			var column_id=parseInt(split_column[0]);
			axios.post('/demos/task/insert', {
    title: title,
	column_id: column_id
  })
  .then(function (response) {
    console.log(response.data);
	window.location.reload();
  })
  .catch(function (error) {
    console.log(error);
  });
			},
			  show (desc,title,id) {
            this.$modal.show('card_modal');
			setTimeout(function(){
			jQuery('#card_title').val(title);
			jQuery('#card_description').val(desc);
			jQuery('#card_id').val(id);
			}, 1000);
        },
        hide () {
            this.$modal.hide('card_modal');
        },
		saveCard()
		{
		var card_title=jQuery('#card_title').val();
			var desc=jQuery('#card_description').val();
			var id=jQuery('#card_id').val();
			axios.patch('/demos/tasks/update/' + id, {
                    title:card_title,
					desc:desc
                }).then((response) => {
                    console.log(response.data);
					window.location.reload();
                }).catch((error) => {
                    console.log(error);
                })
		},
		splitColumn :function(column)
		{
		var split_column = column.split("_");
		return split_column[1];
		},
		addColumn:function()
		{
		
		var title=document.getElementById('add_column_value').value;
			axios.post('demos/column/insert', {
    name: title,
  })
  .then(function (response) {
    console.log(response.data);
	window.location.reload();
  })
  .catch(function (error) {
    console.log(error);
  });
		
		}
		
	

        },
				mounted () {
        
    }
    }
	function objLength(obj){
	var tasks=[];
	Object.entries(obj).forEach(([key, value]) => {
	Object.entries(value).forEach(([key2, value2]) => {
	tasks.push(value2);
});
});
return tasks;
}
	
	
	
</script>
