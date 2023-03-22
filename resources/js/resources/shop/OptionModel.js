import Core from '../../core/index';

import ModelI18n from '../base/ModelI18n';
import SchemaValue from "../schema/SchemaValue";

export default class OptionModel extends ModelI18n {
    constructor(entityData = {}, languages = []) {
        super(entityData, languages);

        let valueTypes = [
            {
                code: 'color'
            }
        ];

        let values = (new SchemaValue(this.getSchemaFields().value)).combine(entityData.value, valueTypes);

        this.value = {};

        for (let i in values) {
            this.value[i] = values[i];

            if (i === 'color') {
                if ('' !== values[i].value) {
                    this.colors = JSON.parse(values[i].value);

                    if (typeof this.colors.rgba === 'undefined' && typeof this.colors.value !== 'undefined') {
                        this.colors = JSON.parse(this.colors.value);
                    }

                    try {
                        this.rgba = `rgba(${this.colors.rgba.r}, ${this.colors.rgba.g}, ${this.colors.rgba.b}, ${this.colors.rgba.a})`;
                    } catch (e) {
                        console.dir('----this.value---');
                        console.dir(this.value);
                        console.dir(this.colors);
                        console.dir(i);

                        this.rgba = 'rgba(0,0,0,1)';
                    }

                    this.color = values[i];
                }
            }
        }
    }

    getSchemaFields() {
        return {
            id: function(data) {
                return data.id ? data.id : Core.uniqueId()
            },

            enabled: true,
            position: 0,

            i18n: {
                value: '',
            },
            value: {
                value: '',
            },
        };
    }
}