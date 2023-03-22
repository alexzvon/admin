import ModelI18n from '../base/ModelI18n';
import PricesTableModel from './PricesTableModel';
import SaleTableModel from "./Sale/SaleTableModel";

export default class ProductModel extends ModelI18n {
    getSchemaFields() {
        return {
            quantity  : 0,
            slug      : '',
            is_payable: true,
            enabled   : true,
            available : true,

            created_at: null,
            updated_at: null,
            receipt_date: null,
            on_order   : false,

            width : 0,
            height: 0,
            length: 0,
            weight: 0,

            delivery_width : 0,
            delivery_height: 0,
            delivery_length: 0,
            delivery_weight: 0,

            categories: [],
            rooms     : [],
            styles    : [],
            kits      : [],
            cargo_places: [],

            badges: [],

            images          : [],
            attributes      : [],
            options         : [],
            suppliers       : [],
            delivery_time   : '',
            production_time : '',
            id_from_supplier: '',
            gtin: '',

            related(data) {
                return (data.related || []).map(item => item.id);
            },

            relatedOptions(data) {
                return data.related || [];
            },

            prices(data) {
                return new PricesTableModel(data.prices);
            },

            sale(data) {
                return new SaleTableModel(data.sale);
            },

            i18n: {
                title           : '',
                description     : '',
                meta_title      : '',
                meta_description: '',
            },
        };
    }
}
