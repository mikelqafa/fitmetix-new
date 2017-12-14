<template>
    <div class="ft-dock-wrapper">
        <div class="ft-chat-group">
            <div class="ft-chat-wrapper">
                <div class="ft-chat-box">
                    <div class="ft-chat-box__inner-wrapper">
                        <header class="ft-chat-box__header">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .chat-item__content-wrapper{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        line-height: 1.28;
        padding: 0 0 1px 0;
    }
    .chat-item__bubble{
        min-height: 24px;
        position: relative;
        background-color: #fafafa;
        border: 0;
        color: #4b4f56;
        min-height: 22px;
        min-width: 14px;
        overflow: hidden;
        position: relative;
        white-space: pre-wrap;
        border-bottom-right-radius: 12px;
        border-top-right-radius: 12px;

    }
    .chat-item {
        margin: 10px 9px 10px 9px;
        position: relative;
        justify-content: flex-start;
        align-items: flex-end;
        display: flex;
        flex-direction: row;
    }
    .chat-item__content{
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
    }
    .chat-item__user{
        position: relative;
    }
    .chat-item__user a {
        display: inline-block;
        height: 32px;
        position: relative;
        width: 32px;
    }
    .chat-item__user img {
        border-radius: 50%;
        height: 32px;
        width: 32px;
    }
    .user_is-online {
        background-color: #42b72a;
        border: 2px solid #fff;
        height: 7px;
        width: 7px;
        border-radius: 50%;
        bottom: 1px;
        position: absolute;
        right: -1px;
        z-index: 1;
        box-sizing: content-box;
    }
    .ft-chat__quick-action {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 28px;
        display: flex;
        flex-direction: row;
        flex-grow: 1;
        align-items: center;
        justify-content: space-around;
    }
    .btn--icon{
        height: 24px;
        width: 24px;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    .btn--icon .icon {
        line-height: 1;
        font-size: 18px;
    }
    .btn--icon--fa{
        font-size: 20px;
    }
    .ft-chat__write-button-wrapper{
        position: absolute;
        top:4px;
        left:auto;
        right:11px;
    }
    .ft-chat__write-button-wrapper svg {
        height: 20px;
        width: 20px;
        margin-left: 1px;
    }
    .ft-chat__write-button-wrapper .btn {
        height: 28px;
        width: 28px;
        border-radius: 50%;
        padding: 3px;
        background-color: #1E7C82;
        box-shadow: 0 0 0 1px rgba(0,0,0,.12);
    }
    .ft-chat__write-box {
        cursor: text;
        flex-grow: 1;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,.12);
        padding: 4px 10px;
        min-height: 32px;
        max-height: 80px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .ft-chat-box__inner-wrapper {
        height: 320px;
        display: flex;
        flex-direction: column;
        overflow-x: hidden;
    }
    .inner-chat-wrapper {
        background: #fff;
        overflow: hidden;
        height: 100%;
        width: 100%;
        flex-grow: 1;
    }
    .ft-chat-box__footer{
        background-color: #fff;
        padding-bottom: 28px;
        padding-top: 2px;
        position: relative;
        display: flex;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        flex-shrink: 0;
    }
    .ft-chat__write {
        max-height: 90px;
        min-height: 16px;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 2px 4px;
        position: relative;
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .inner-chat {
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
    }
    .ft-chat-box__body{
        position: relative;
        flex-grow: 1;
        display: flex;
        flex-direction:column;
    }
    .ft-dock-wrapper{
        bottom: 0;
        height: 32px;
        left: 0;
        position: fixed;
        right: 0;
        z-index: 300;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        margin: 0 24px;
    }
    .ft-chat-group{
        display: flex;
        flex-direction: row;
    }
    .ft-chat-box {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: auto;
        left: auto;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .3);
        right: 0;
    }
    .ft-chat-wrapper{
        position: relative;
        height: 32px;
        width: 270px;
    }
    .ft-chat-box__header{
        background-color: #1E7C82;
        color: #FFF;
        display: flex;
        flex-direction: row;
        height: 32px;
        align-items: center;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .20);
        z-index: 2;
        border-radius: 3px 3px 0 0;
    }
    .ft-chat-box a {
        color: inherit;
    }
    .ft-chat-box .chat-options .icon {
        font-size: 14px;
    }
    .ft-chat-box .chat-options{
        width: 28px;
        height: 28px;
        line-height: 28px;
    }
    .margin-right-8 {
        margin-right: 8px;
    }
    .margin-left-8 {
        margin-left: 8px;
    }
</style>