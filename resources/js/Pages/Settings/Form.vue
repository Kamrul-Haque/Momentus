<script setup>
import Admin from "@/Layouts/Admin.vue";
import {Head} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import FileInput from "@/Components/FileInput.vue";
import {useForm} from "laravel-precognition-vue-inertia";

const props = defineProps({
    setting: {
        type: Object
    }
});

const form = useForm('post', route('settings.update', props.setting), {
    _method: 'put',
    name: props.setting ? props.setting.name : null,
    email: props.setting ? props.setting.email : null,
    address: props.setting ? props.setting.address : null,
    admin_email: props.setting ? props.setting.admin_email : null,
    support_email: props.setting ? props.setting.support_email : null,
    phone: props.setting ? props.setting.phone : null,
    mobile: props.setting ? props.setting.mobile : null,
    whatsapp: props.setting ? props.setting.whatsapp : null,
    description: props.setting ? props.setting.description : null,
    copyright_text: props.setting ? props.setting.copyright_text : null,
    logo: null,
    alternate_logo: null,
    portal_logo: null,
    favicon: null,
    primary_color: props.setting ? props.setting.primary_color : null,
    secondary_color: props.setting ? props.setting.secondary_color : null,
    accent_color: props.setting ? props.setting.accent_color : null,
    background_color: props.setting ? props.setting.background_color : null,
    table_stripe_color: props.setting ? props.setting.table_stripe_color : null,
    under_maintenance: props.setting ? props.setting.under_maintenance : null,
});

function submit() {
    form.submit({
        preserveState: (page) => Object.keys(page.props.errors).length > 0
    });
}
</script>

<template>
    <Head title="Settings"/>

    <Admin>
        <div class="card w-full md:w-3/4 mx-auto">
            <h1 class="card-header">Settings</h1>
            <div class="card-body">
                <form @submit.prevent="submit()">
                    <div class="grid grid-cols-2 gap-x-4">
                        <TextField v-model="form.name"
                                   label="Name"
                                   class="col-span-2 md:col-span-1"
                                   required
                                   @change="form.validate('name')"
                                   :error="form.errors.name"/>

                        <TextField v-model="form.email"
                                   type="email"
                                   class="col-span-2 md:col-span-1"
                                   required
                                   label="Email"
                                   @change="form.validate('email')"
                                   :error="form.errors.email"/>

                        <TextField v-model="form.support_email"
                                   type="email"
                                   class="col-span-2 md:col-span-1"
                                   label="Support Email"
                                   @change="form.validate('support_email')"
                                   :error="form.errors.support_email"/>

                        <TextField v-model="form.admin_email"
                                   type="email"
                                   class="col-span-2 md:col-span-1"
                                   label="Admin Email"
                                   @change="form.validate('admin_email')"
                                   :error="form.errors.admin_email"/>

                        <TextField v-model="form.phone"
                                   class="col-span-2 md:col-span-1"
                                   required
                                   label="Phone"
                                   @change="form.validate('phone')"
                                   :error="form.errors.phone"/>

                        <TextField v-model="form.mobile"
                                   class="col-span-2 md:col-span-1"
                                   label="Mobile"
                                   @change="form.validate('mobile')"
                                   :error="form.errors.mobile"/>

                        <TextField v-model="form.whatsapp"
                                   class="col-span-2 md:col-span-1"
                                   label="Whatsapp"
                                   @change="form.validate('whatsapp')"
                                   :error="form.errors.whatsapp"/>

                        <TextField v-model="form.primary_color"
                                   required
                                   placeholder="#000000"
                                   class="col-span-2 md:col-span-1"
                                   label="Primary Color"
                                   @change="form.validate('primary_color')"
                                   :error="form.errors.primary_color"/>

                        <TextField v-model="form.secondary_color"
                                   required
                                   placeholder="#000000"
                                   class="col-span-2 md:col-span-1"
                                   label="Secondary Color"
                                   @change="form.validate('secondary_color')"
                                   :error="form.errors.secondary_color"/>

                        <TextField v-model="form.accent_color"
                                   required
                                   placeholder="#000000"
                                   class="col-span-2 md:col-span-1"
                                   label="Accent Color"
                                   @change="form.validate('accent_color')"
                                   :error="form.errors.accent_color"/>

                        <TextField v-model="form.background_color"
                                   required
                                   placeholder="#000000"
                                   class="col-span-2 md:col-span-1"
                                   label="Background Color"
                                   @change="form.validate('background_color')"
                                   :error="form.errors.background_color"/>

                        <TextField v-model="form.table_stripe_color"
                                   required
                                   placeholder="#000000"
                                   class="col-span-2 md:col-span-1"
                                   label="Table Stripe Color"
                                   @change="form.validate('table_stripe_color')"
                                   :error="form.errors.table_stripe_color"/>

                        <TextField v-model="form.copyright_text"
                                   label="Copyright Text"
                                   class="col-span-2"
                                   @change="form.validate('copyright_text')"
                                   :error="form.errors.copyright_text"/>

                        <TextArea v-model="form.address"
                                  label="Address"
                                  class="col-span-2"
                                  @change="form.validate('address')"
                                  :error="form.errors.address"/>

                        <FileInput v-model="form.logo"
                                   type="file"
                                   label="Logo"
                                   class="col-span-2"
                                   @change="form.validate('logo')"
                                   :error="form.errors.logo"/>

                        <FileInput v-model="form.favicon"
                                   type="file"
                                   label="Favicon"
                                   class="col-span-2"
                                   @change="form.validate('favicon')"
                                   :error="form.errors.favicon"/>

                        <FileInput v-model="form.alternate_logo"
                                   type="file"
                                   label="Alternate Logo"
                                   class="col-span-2"
                                   @change="form.validate('alternate_logo')"
                                   :error="form.errors.alternate_logo"/>

                        <FileInput v-model="form.portal_logo"
                                   type="file"
                                   label="Portal Logo"
                                   class="col-span-2"
                                   @change="form.validate('portal_logo')"
                                   :error="form.errors.portal_logo"/>
                    </div>

                    <div class="text-right mt-8">
                        <PrimaryButton type="submit">Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Admin>
</template>
