<template>
    <div class="tabs">
        <div v-for="tab in tabs"
             :key="tab.name"
             :class="['tab', {'active': props.activeTab === tab.name}]"
             @click="setActiveTab(tab)">
                {{ tab.label }}
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        tabs: {
            type: Array,
            default: () => {
                return [
                    {
                        name: 'tab',
                        label: 'Tab',
                    }
                ]
            }
        },
        activeTab: {
            type: String,
            default: () => 'tab',
        },
    });

    const emit = defineEmits(['update:active-tab']);

    function setActiveTab(tab) {
        emit('update:active-tab', tab.name);
    }
</script>


<style lang="scss">
    .tabs {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid rgba(233,233,233,.9);

        .tab {
            padding: 0.5rem 1rem;
            border-bottom: none;
            cursor: pointer;
            background: #fff;
            margin-right: 0.5rem;
            color: #000;
            font-weight: 900;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border-bottom: 2px solid transparent;

            &:hover {
                background: #eee;
                border-bottom: 2px solid rgba(33,180,180, .5);
            }

            &.active {
                border-bottom: 2px solid rgba(33,180,180,.9);
            }
        }
    }
</style>
