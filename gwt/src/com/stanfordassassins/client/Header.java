/**
 * 
 */
package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.ui.Anchor;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.InlineLabel;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Vadim
 *
 */
public class Header extends Composite {

	private static HeaderUiBinder uiBinder = GWT.create(HeaderUiBinder.class);

	interface HeaderUiBinder extends UiBinder<Widget, Header> {
	}

	@UiField
	InlineLabel nameLabel;
	@UiField
	Anchor logOutAnchor;

	public Header(String name) {
		initWidget(uiBinder.createAndBindUi(this));
	
		nameLabel.setText(" " + name);
	}

	@UiHandler("logOutAnchor")
	void onClick(ClickEvent e) {
		//
	}

}
