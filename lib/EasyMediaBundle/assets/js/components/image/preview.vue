<template>
    <div class="wrapper">
        <div data-img-container>
            <slot/>
        </div>

        <div class="goo">
            <div v-if="showOps"
                 class="circular-menu"
                 :class="{'active' : opsMenu}">
                <div class="floating-btn"
                     @click="toggleOpsMenu()">
                    <span class="icon is-large"><icon name="cog"/></span>
                </div>

                <menu class="items-wrapper">
                    <!-- move -->
                    <div class="menu-item">
                        <button type="button" class="button btn-plain"
                                :disabled="ops_btn_disable"
                                @click.stop="addToMovableList()">
                            <span class="icon is-large">
                                <icon v-if="inMovableList()"
                                      name="shopping-cart"
                                      scale="1.2"/>
                                <icon v-else
                                      name="cart-plus"
                                      scale="1.2"/>
                            </span>
                        </button>
                    </div>

                    <!-- edit metas -->
                    <div class="menu-item">
                      <button type="button" class="button btn-plain"
                              :disabled="ops_btn_disable"
                              @click.stop="editMetasItem()">
                              <span class="icon is-large">
                                  <icon name="hashtag"
                                        scale="1.2"/>
                              </span>
                      </button>
                    </div>

                    <!-- rename -->
                    <div class="menu-item">
                        <button type="button" class="button btn-plain"
                                :disabled="ops_btn_disable"
                                @click.stop="renameItem()">
                            <span class="icon is-large">
                                <icon name="i-cursor"
                                      scale="1.2"/>
                            </span>
                        </button>
                    </div>

                    <!-- editor -->
                    <div class="menu-item">
                        <button type="button" class="button btn-plain"
                                :disabled="ops_btn_disable"
                                @click.stop="imageEditorCard()">
                            <span class="icon is-large">
                                <icon name="regular/object-ungroup"
                                      scale="1.2"/>
                            </span>
                        </button>
                    </div>

                    <!-- delete -->
                    <div class="menu-item bg-danger">
                        <button type="button" class="button btn-plain"
                                :disabled="ops_btn_disable"
                                @click.stop="deleteItem()">
                            <span class="icon is-large">
                                <icon name="regular/trash-alt"
                                      scale="1.2"/>
                            </span>
                        </button>
                    </div>
                </menu>
            </div>
        </div>
    </div>
</template>

<script>
import debounce        from 'lodash/debounce'
import animateScrollTo from '../../packages/animated-scroll-to'

export default {
    props: [
        'trans',
        'showOps',
        'ops_btn_disable',
        'inMovableList',
        'renameItem',
        'editMetasItem',
        'deleteItem',
        'imageEditorCard',
        'addToMovableList'
    ],
    data() {
        return {
            opsMenu: false
        }
    },
    methods: {
        getContainer(el) {
            return el.querySelector('[data-img-container]')
        },
        toggleOpsMenu() {
            return this.opsMenu = !this.opsMenu
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../../../sass/modules/scroll-btn';
@import '../../../sass/packages/goo';

.wrapper {
    overflow: hidden;
    position: relative;
    display: block;

    > div:first-child {
        display: block;
        padding-bottom: 62.5%;
        @screen sm {
          width: 60vh;
        }
    }
}

</style>
