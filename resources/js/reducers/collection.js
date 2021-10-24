import { handleActions } from 'redux-actions';

const initialState = [
	{
		id: 'recommend',
		title: 'Рекоммендации',
		items: [
			1,2,3,4,5
		],
	}
];

const collectionReducer = handleActions({
    collection: {
        update: (state, { payload }) => [state].filter((item) => payload.find((payloadItem)=>payloadItem.id == item.id)),
    },
}, initialState);

export default collectionReducer;
