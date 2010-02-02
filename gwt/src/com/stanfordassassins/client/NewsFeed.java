/**
 * 
 */
package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DisclosurePanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Vadim
 *
 */
public class NewsFeed extends Composite {

	private static NewsFeedUiBinder uiBinder = GWT
			.create(NewsFeedUiBinder.class);

	interface NewsFeedUiBinder extends UiBinder<Widget, NewsFeed> {
	}

	@UiField
	FlexTable feedTable;

	public NewsFeed(Player result) {
		initWidget(uiBinder.createAndBindUi(this));

		addRow("JellyBelly", "Spiderman", "While he was tying his shoe, I hit him with a sock right on the face.");
		addRow("PennyLane", "Ms. Robinson", "In the middle of the street.");
		addRow("ApplePie", "Vegan", "During his dinner.");
	}

	private void addRow(String killer, String victim, String details) {
		feedTable.insertRow(0);
		DisclosurePanel panel = new DisclosurePanel();
		panel.setHeader(new HTML( "<b>" + killer + " killed " + victim + ".</b> Details..."));
		panel.setContent(new Label("According to " + killer + ", " + details));
		panel.setOpen(true);
		feedTable.setWidget(0, 0, panel);
	}

}
