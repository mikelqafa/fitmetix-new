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
                                <div class="inner-chat"></div>
                            </div>
                        </div>
                        <div class="ft-chat-box__footer">
                            <div class="ft-chat__write">
                                <div aria-autocomplete="list" aria-controls="js_31o" aria-expanded="false" class="notranslate _5rpu" contenteditable="true" role="combobox" spellcheck="true" style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"><div data-contents="true"><div data-block="true" data-editor="65i3r" data-offset-key="rklg-0-0"><div data-offset-key="rklg-0-0" class="_1mf _1mj"><span data-offset-key="rklg-0-0"><span data-text="true">d</span></span></div></div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .ft-chat-box__inner-wrapper {
        height: 320px;
        display: flex;
        flex-direction: column;
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
        padding-bottom: 25px;
        position: relative;
        display: flex;
        flex-shrink: 0;
    }
    .ft-chat__write {
        border-top: 1px solid #c9d0da;
        cursor: text;
        max-height: 90px;
        min-height: 16px;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 10px 8px 6px;
        position: relative;
        width: 100%;
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
        width: 272px;
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