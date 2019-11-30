<template>
   
         <button class="btn btn-primary" type="submit" :class="classes"  @click="toggle">
             <span><i class="fas fa-heart"></i></span>
               <span v-text="count"></span>    
        </button>
   
</template>

<script>
    export default {
        props: ["reply"],

        data()
        {
            return{
                count: this.reply.favorites_count,
                active: this.reply.isFavorited
            }

        },
        computed:{
            classes(){
                return ['btn', this.active? 'btn-primary': 'btn-secondary']
            }
        },
        methods:{
            toggle(){
                axios.post("/replies/" + this.reply.id + "/favorites");
                if(this.active)
                {
                   // axios.post("/replies/" + this.reply.id + "/favorites");
                    this.active = false;
                    this.count--;
                    flash("like removed");
                }   
                else{
                   // axios.post("/replies/" + this.reply.id + "/favorites");
                    this.active = true;
                    this.count++;
                    flash("like added");
                }
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>