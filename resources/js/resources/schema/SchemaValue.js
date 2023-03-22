import Schema from './Schema';

export default class SchemaValue extends Schema {
    combine(data = [], types = []) {
        let c = this.__combine(this.schema, data, types);

        return c;
    }

    __combine(schema, data = [], types = []) {
        return types.reduce((acc, type) => {
            acc[type.code] = super.__combine(schema, data.find(item => {
                return item.type === type.code;
            }));

            return acc;
        }, {});
    }
}
