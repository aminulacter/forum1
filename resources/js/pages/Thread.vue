
<script>
import Replies from "../components/Replies"
    export default {
        props: ['initialreplycount'],
        components: {Replies},
        data()
        {
            return{
                repliesCount: this.initialreplycount,
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>