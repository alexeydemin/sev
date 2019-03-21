<template>
  <div>
    <ul class="list-group">
      <li class="list-group-item" v-for="msg in messages">
        {{msg}}
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['streamerId'],
    data() {
      return {
        messages: []
      }
    },
    mounted() {
      this.$echo.channel('events_of_' + this.streamerId)
        .listen('StreamEventOccurred', (e) => {
          console.log(e.message);
          if (this.messages.length > 9) {
            this.messages.pop();
          }
          this.messages.unshift(e.message);
        });
    }
  }
</script>
