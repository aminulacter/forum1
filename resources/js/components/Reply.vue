<template>
     <!-- <reply :attributes="{{ $reply }}" inline-template v-cloak> -->
 <div :id="'reply-'+id" class="card mt-3" >
      <div class="card-header">
            <div class="level">
                <h5 class="flex">
                        <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a> 
                         said {{ data.created_at }} ...
                </h5>
          
               <div v-if="signedIn">
                     <favorite :reply="data"></favorite>
                     
               </div>
           
            
            </div>
             
     </div>

     <div class="card-body">
        <div v-if="editing">
          <div class="form-group">
               <textarea id="my-textarea" class="form-control" name="body" rows="3" v-model="body"></textarea>
          </div>
           <button class="btn btn-sm btn-primary" @click="update">Update</button>
           <button class="btn btn-sm btn-link" @click="editing = false">cancel</button>
           
         
        </div>
        <div v-else v-text = "body">
           
        </div>
            
    </div>
   
      <div class="card-footer level" v-if="canUpdate">
         <button class="btn btn-outline-primary btn-xs mr-2" @click="editing = true">Edit</button>
         <button class="btn btn-danger btn-xs mr-2" @click="destroy">Delete</button>
       
      </div>
   
    

 </div>
 <!-- </reply> -->
                      
</template>

<script>
import Favorite from './Favorite.vue';
    export default {
        props: ['data'],
        components: {
            Favorite
        },
        data(){
            return{
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },
        computed: {
            signedIn()
            {
                return window.App.signedIn;
            },
            canUpdate()
            {
                return this.authorize( user => this.data.user_id == user.id)
                //return this.data.user_id == window.App.user.id
            }
        },
        methods:{
            update()
            {
                axios.patch('/replies/'+ this.data.id,{
                    body: this.body
                }).then(response => {
                    this.editing = false
                    flash("updated");
                    })
                .catch(error => this.edit=false)
                
            },
            destroy()
            {
                axios.delete('/replies/'+ this.data.id);
                this.$emit('deleted', this.data.id);
                
                
            }
        }    
       }
</script>

<style lang="scss" scoped>

</style>