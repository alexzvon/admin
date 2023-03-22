import ModelI18n from '../base/ModelI18n'

export default class CategoryModel extends ModelI18n {
    getSchemaFields() {
        return {

            parent_id: '',
            slug     : '',
            enabled  : true,

            image          : false,
            miniature_image: '',
            is_popular     : false,

            created_at: null,
            updated_at: null,

            i18n: {
                title           : '',
                description     : '',
                meta_title      : '',
                meta_description: '',
            },
        }
    }
}