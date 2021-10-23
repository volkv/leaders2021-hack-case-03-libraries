import React from 'react';
import WidgetCollection from '../WidgetCollection';

const PersonModal = (props) => {
	return (
		<div>
			<h2>User {props.user_id}</h2> 
			<div className='prop-field'><label>Всего прочитано книг:</label> {props.all_count}</div>
			<div className='prop-field'><label>Пересечений по прочитанному:</label> {props.common_cnt}</div>
			<div className='prop-field'><label>Фактор схожести:</label> {props.factor}</div>
			<WidgetCollection
				title={'Совпадения прочитанных книг'}
				items={props.common}
				child='Book'
			/>
			<WidgetCollection
				title={'Еще не прочитанные вами книги'}
				items={props.diff}
				child='Book'
			/>
		</div>
	);
};

export default PersonModal;