<template>
    <div>
          
            <div class="mt-3" v-if="signedIn">
                   
                        <div class="form-group">
                            
                            <textarea id="body" 
                            class="form-control" 
                            name="body" rows="5"  
                            placeholder="have something to say?"
                            required
                             v-model="body"></textarea>
                        </div>    
                        
                        <button type="submit" 
                        class="btn btn-primary"
                        
                        @click="addReply">Post</button>  
                            
                
            </div>
           <div v-else>
                 <p>Please <a href="/login"> sign in </a> to participate in this discussion</p>
           </div>
          
           
    </div>
</template>

<script>
   import 'jquery.caret'
   import 'at.js';
    export default {
        
        data() {
            return {
                body: '',
               
            }
        },
       
        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks:{
                    remoteFilter: function(query, callback){
                        $.getJSON("/api/users", {name: query}, function(username){
                            callback(username)
                        })
                    }
                }

            });
        },
        methods: {
            addReply()
            {
                axios.post(location.pathname + '/replies', { body: this.body})
                .then(({data}) =>{
                    this.body = ''
                    flash('Your reply has been posted')
                    this.$emit('created', data)
                })
                .catch( error => flash(error.response.data.message, 'danger'));
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>