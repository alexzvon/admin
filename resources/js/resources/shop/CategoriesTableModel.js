import ModelI18n from "../base/ModelI18n";
import HasImage from "../base/HasImage";

export default class CategoriesTableModel extends ModelI18n {
    constructor(entityData, languages) {
        super(entityData, languages);

        let children = entityData.children;

        if (children && children instanceof Array && children.length > 0) {
            this.children = children.map(item => new CategoriesTableModel(item, languages)).sort((a, b) => a.position - b.position);
        }
    }

    getSchemaFields() {
        return {
            id: "",
            parent_id: "",
            slug: "",
            enabled: true,
            products_count: 0,
            position: 0,

            image(data) {
                /*
                * @s3
                if (data.miniature_image) {
                    return {
                        src: data.miniature_image,
                        srcset: data.miniature_image
                    };
                }*/
                return HasImage("small")(data);
            },

            url(data) {
                return "/shop/categories/" + data.id;
            },

            siteUrl(data) {
                return "https://candellabra.com/catalog/" + data.slug;
            },

            i18n: {
                title: "<span class=\"label label-danger\">Не заполнено</span>"
            }
        };
    }
}