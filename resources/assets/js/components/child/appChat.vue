<template>
    <div class="ft-dock-wrapper">
        <div class="ft-chat-group">
            <div class="ft-chat-wrapper">
                <div class="ft-chat-box">
                    <div class="ft-chat-box__inner-wrapper">
                        <header class="ft-chat-box__header">
                            <a href="javascript:;" class="chat-user margin-left-8" @click="openChat">
                                <svg fill="#ffffff" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                                </svg>
                            </a>
                            <a href="javascript:;" class="chat-user margin-left-8">Prakash</a>
                            <div class="md-layout-spacer"></div>
                            <a href="javascript:;" class="chat-options">
                                <i class="icon icon-options"></i>
                            </a>
                            <a href="javascript:;" class="chat-options margin-right-8">
                                <i class="icon icon-close"></i>
                            </a>
                        </header>
                        <div class="ft-chat-box__body">
                            <div class="inner-chat-wrapper">
                                <div class="inner-chat">
                                    <div class="chat-item">
                                        <div class="chat-item__user">
                                            <a aria-label="Prem Bharti Wednesday 7:08pm" class="_4tdw" data-hover="tooltip" data-tooltip-content="Prem Bharti Wednesday 7:08pm" data-tooltip-position="left" href="https://www.facebook.com/prembharti786">
                                                <img src="https://scontent.fccu2-1.fna.fbcdn.net/v/t1.0-1/p50x50/14713516_1171655996236869_9059786452811945365_n.jpg?oh=ff46ba9eebd83f610e7dd085a1c301cb&amp;oe=5AC4DE34" alt="" class="img">
                                            </a>
                                            <div class="user_is-online"></div>
                                        </div>
                                        <div class="chat-item__content">
                                            <div class="chat-item__content-wrapper">
                                                <div class="chat-item__content-text">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ft-chat-box__footer">
                            <div class="ft-chat__write">
                                <div aria-autocomplete="list" aria-controls="js_31o" aria-expanded="false" class="ft-chat__write-box notranslate _5rpu" contenteditable="true" role="combobox" spellcheck="true" style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"><div data-contents="true"><div data-block="true" data-editor="65i3r" data-offset-key="rklg-0-0"><div data-offset-key="rklg-0-0" class="_1mf _1mj"><span data-offset-key="rklg-0-0"><span data-text="true">d</span></span></div></div></div></div>
                                <div class="ft-chat__write-button-wrapper">
                                    <button type="submit" class="btn ft-chat__write-button">
                                        <svg class="svg-icon" fill="#ffffff" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                            <path d="M0 0h24v24H0z" fill="none"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="ft-chat__quick-action">
                                <a href="javascript:;" class="btn btn--icon">
                                    <i class="icon icon-add"></i>
                                </a>
                                <a href="javascript:;" class="btn btn--icon">
                                    <i class="icon icon-photo"></i>
                                </a>
                                <a href="javascript:;" class="btn btn--icon btn--icon--fa">
                                    <i class="fa fa-smile-o"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ft-chat--list-wrapper is-list-open">
                            <header class="ft-chat-box__header">
                                <a href="javascript:;" class="chat-user margin-left-8">Chat Story</a>
                                <div class="md-layout-spacer"></div>
                                <a href="javascript:;" class="chat-options margin-right-8">
                                    <i class="icon icon-close"></i>
                                </a>
                            </header>
                            <div class="ft-chat-box__body pos-rel">
                                <div class="scroll-wrapper">
                                    <div class="md-list md-list--dense">
                                        <div v-for="it in item" class="md-list__item" @click="openChat">
                                            <div class="md-list__item-content">
                                                <a href="//localhost:3000/fitmetix/public/doremon" class="md-list__item-icon user-avatar" style="background-image: url('http://localhost/fitmetix/public/user/avatar/2017-12-29-10-26-15doraemon-doremon-hd-arena-163807.png')">
                                                </a>
                                                <div class="md-list__item-primary">
                                                    <span>Doremon</span>
                                                    <div class="md-list__item-text-body">Doremon your event...</div>
                                                </div>
                                                <div class="md-list__item-secondary text-right">
                                                    <div class="md-list__item-secondary-info">
                                                        <time datetime="Sat Dec 30 2017 16:55:32 GMT+0530 (IST)" title="30/12/2017, 16:55:32" class="timeago">4 d</time>
                                                    </div>
                                                    <div class="md-layout-spacer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  export default {
    data: function () {
      return {
          item: [1,2,3,4,5,6,7,8,9]
      }
    },
      methods: {
        openChat: function () {
            $('.ft-chat--list-wrapper').toggleClass('is-list-open')
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

</style>
