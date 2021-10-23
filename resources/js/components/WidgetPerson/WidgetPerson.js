import React, { useState } from 'react';
import PersonImage from './PersonImage';
import PersonModal from './PersonModal';
import Modal from '../Modal';

const WidgetPerson = (props) => {
	const {
		user_id,
		all_count,
		common_cnt,
		factor
	} = props;
	const [modal, setModal] = useState(false);

	return ( <>
		<div className='book' onClick={() => setModal(true)}>
			<PersonImage />
			<span className='title'>User { user_id }</span>
			<span className='author'>Всего прочитано: { all_count }</span>
			<span className='author'>Общих книг: { common_cnt }</span>
			<span className='author'>Фактор: { Number(factor).toFixed(2) }</span>			
		</div>
		{ modal && <Modal onClose={() => setModal(false)}>
			<PersonModal {...props} />
		</Modal> }
	</> );
};

export default WidgetPerson;