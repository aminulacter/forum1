<template>
     <!-- <reply :attributes="{{ $reply }}" inline-template v-cloak> -->
 <div :id="'reply-'+id" class="card  mt-3">
      <div class="card-header" :style="cardColor">
            <div class="level">
                <h5 class="flex">
                        <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a> 
                         said <span v-text="ago"></span> ...
                </h5>
          
               <div v-if="signedIn">
                     <favorite :reply="reply"></favorite>
                     
               </div>
            </div>
             
     </div>

     <div class="card-body">
        <div v-if="editing">
            <form @submit="update">
                <div class="form-group">
                    <textarea id="my-textarea" class="form-control" name="body" rows="3" v-model="body" required></textarea>
                </div>
                <button class="btn btn-sm btn-primary" type="submit">Update</button>
                <button class="btn btn-sm btn-link"  type="button" @click="cancel">cancel</button>
           
            </form>
        </div>
        <div v-else v-html = "body">
           
        </div>
            
    </div>
   
      <div class="card-footer level"  v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
        <div v-if="authorize('owns', reply)">
         <button class="btn btn-outline-primary btn-xs mr-2" @click="editing = true">Edit</button>
         <button class="btn btn-danger btn-xs mr-2" @click="destroy">Delete</button>
        </div>
          <button class="btn btn-outline-secondary btn-xs mr-2" style="margin-left: auto" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply</button>

       
      </div>
   
    

 </div>
 <!-- </reply> -->
                      
</template>

<script>
import moment from 'moment';
import Favorite from './Favorite.vue';
    export default {
        props: ['reply'],
        components: {
            Favorite
        },
        data(){
            return{
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
                
            }
        },
        computed: {
                      
            ago()
            {
               return moment( this.reply.created_at).fromNow()
            },
            cardColor()
            {
                if(this.isBest)
                {
                    return {'background-color':  '#d6eeb7'}                  
                }
                return {}
            }
        },
        created(){
            console.log('found')
            window.events.$on('best-reply-selected', id => this.isBest = id === this.id)
        },
        methods:{
            update()
            {
               this.editing = false
          
               axios.patch('/replies/'+ this.id,{
                    body: this.body
                }).then(response =>  flash("updated"))
                .catch(error => {
                    flash(error.response.data.message, 'danger')
                   this.body = this.data.body
                })
            },
            cancel()
            {
                  this.editing = false
                 this.body = this.data.body
            },
            destroy()
            {
                 axios.delete('/replies/'+ this.id);
                this.$emit('deleted');
 
            },
            markBestReply()
            {
                axios.post('/replies/' + this.id + '/best')
                window.events.$emit('best-reply-selected', this.id)
            }
        }    
       }
</script>

<style lang="scss" scoped>

</style>