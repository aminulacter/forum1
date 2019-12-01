
<script>
import Replies from "../components/Replies"
import SubscribeButton from "../components/SubscribeButton"
    export default {
        props: ['initialreplycount'],
        components: {Replies, SubscribeButton},
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