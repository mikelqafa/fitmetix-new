<template>
  <div class="center-block" style="max-width: 1024px">
      <div  class="event-filter-wrapper event-filter-wrapper--search">
          <div style="position: relative;" class="layout-m-t-1 layout-m-b-1">
              <input v-on:keyup="searchApp" v-model="ftSearch" placeholder="Search for user, event and tags" type="text" class="form-control">
          </div>
          <ul class="nav nav--event-filter nav-justified">
              <li class="active">
                  <a data-toggle="tab" href="#search-user">
                      <i class="icon icon-participant hidden-active"></i>
                      <i class="icon icon-participant hidden-inactive"></i>
                  </a>
              </li>
              <li>
                  <a data-toggle="tab" href="#search-event">
                      <i class="icon icon-eventpage hidden-active" style="line-height: 16px; height: 24px"></i>
                      <i class="icon icon-eventpage hidden-inactive" style="line-height: 16px; height: 24px"></i>
                  </a>
              </li>
              <li>
                  <a data-toggle="tab" href="#search-tag">
                      <i class="icon icon-tag hidden-active"></i>
                      <i class="icon icon-tag-o hidden-inactive"></i>
                  </a>
              </li>
          </ul>
          <div class="tab-content tab-content--event-filter">
              <div id="search-user" class="tab-pane fade in active">
                  <div class="panel panel--search">
                      <div class="panel-heading">
                          Users
                      </div>
                      <div class="panel-body">
                          <div class="search-result-wrapper md-list">
                              <a href="" class="md-list__item  has-divider">
                                  <div class="md-list__item-content">
                                      <a href="//localhost:3000/fitmetix/public/doremon" class="md-list__item-icon">
                                          <img src="http://localhost/fitmetix/public/user/avatar/2017-12-29-10-26-15doraemon-doremon-hd-arena-163807.png" alt="Mikel" class="md-list__item-avatar">
                                      </a>
                                      <div class="md-list__item-primary">
                                          <a href="//localhost:3000/fitmetix/public/doremon">Doremon</a>
                                          <div class="md-list__item-text-body">Doremon attending your event</div>
                                      </div>
                                      <div class="md-list__item-secondary">
                                          <div class="md-list__item-secondary-info">
                                              <time datetime="Sat Dec 30 2017 16:55:32 GMT+0530 (IST)" title="30/12/2017, 16:55:32" class="timeago">5 d</time>
                                          </div>
                                          <a href="#" class="md-list__item-secondary-action">
                                              <i class="hidden material-icons">star</i></a>
                                      </div>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
              <div id="search-event" class="tab-pane fade">
                  <div class="panel panel--search">
                      <div class="panel-heading">
                          Events
                      </div>
                      <div class="panel-body">
                          <div class="search-result-wrapper md-list">
                              <transition-group name="flip-list" tag="div">
                                  <a href="" class="md-list__item  has-divider">
                                      <div class="md-list__item-content">
                                          <a href="//localhost:3000/fitmetix/public/doremon" class="md-list__item-icon">
                                              <img src="http://localhost/fitmetix/public/user/avatar/2017-12-29-10-26-15doraemon-doremon-hd-arena-163807.png" alt="Mikel" class="md-list__item-avatar">
                                          </a>
                                          <div class="md-list__item-primary">
                                              <a href="//localhost:3000/fitmetix/public/doremon">Doremon</a>
                                              <div class="md-list__item-text-body">Doremon attending your event</div>
                                          </div>
                                          <div class="md-list__item-secondary">
                                              <div class="md-list__item-secondary-info">
                                                  <time datetime="Sat Dec 30 2017 16:55:32 GMT+0530 (IST)" title="30/12/2017, 16:55:32" class="timeago">5 d</time>
                                              </div>
                                              <a href="#" class="md-list__item-secondary-action">
                                                  <i class="hidden material-icons">star</i></a>
                                          </div>
                                      </div>
                                  </a>
                              </transition-group>
                          </div>
                      </div>
                  </div>
              </div>
              <div id="search-tag" class="tab-pane fade">
                  <div class="panel panel--search">
                      <div class="panel-heading">
                          Tags
                      </div>
                      <div class="panel-body">
                          <div class="search-result-wrapper md-list">
                              <a href="" class="md-list__item  has-divider">
                                  <div class="md-list__item-content">
                                      <a href="//localhost:3000/fitmetix/public/doremon" class="md-list__item-icon">
                                          <img src="http://localhost/fitmetix/public/user/avatar/2017-12-29-10-26-15doraemon-doremon-hd-arena-163807.png" alt="Mikel" class="md-list__item-avatar">
                                      </a>
                                      <div class="md-list__item-primary">
                                          <a href="//localhost:3000/fitmetix/public/doremon">Doremon</a>
                                          <div class="md-list__item-text-body">Doremon attending your event</div>
                                      </div>
                                      <div class="md-list__item-secondary">
                                          <div class="md-list__item-secondary-info">
                                              <time datetime="Sat Dec 30 2017 16:55:32 GMT+0530 (IST)" title="30/12/2017, 16:55:32" class="timeago">5 d</time>
                                          </div>
                                          <a href="#" class="md-list__item-secondary-action">
                                              <i class="hidden material-icons">star</i></a>
                                      </div>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="search-start text-center" style="min-height: 60vh">
              <h3>Search Fitmetix</h3>
          </div>
      </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
          ftSearch: ''
      }
    },
    methods: {
        searchApp: function (e) {
            console.log(e.target.value)
        },
        getList: function () {
            let that = this
            let _token = $("meta[name=_token]").attr('content')
            this.searchList = []
            axios({
                method: 'post',
                responseType: 'json',
                url: base_url + 'ajax/get-registered-users-for-event',
                data: {
                    event_id: that.eventWho.eventId,
                    user_id: user_id,
                    _token: _token
                }
            }).then(function (response) {
                if (response.status == 200) {
                    console.log(response.data)
                    for(let i = 0;i<response.data.length; i++) {
                        that.searchList.push(response.data[i])
                    }
                }
            }).catch(function (error) {
                console.log(error)
            })
        }
    },
    computed: {
      vComputed () {
        return ''
      }
    },
    mounted () {

    }
  }
</script>
<style>
    .flip-list-move {
        transition: transform 1s;
    }
</style>

