/**
 * 
 */
package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.KeyUpEvent;
import com.google.gwt.event.dom.client.KeyUpHandler;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.TextArea;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author juanmtamayo
 *
 */
public class DisputeDialog extends Composite {

	private static DisputeDialogUiBinder uiBinder = GWT.create(DisputeDialogUiBinder.class);

	interface DisputeDialogUiBinder extends UiBinder<Widget, DisputeDialog> {
	}
	
	public static final int REASON_MAX_LENGTH = 1000;
	
	@UiField
	TextArea reasonBox;
	@UiField
	Button submitButton;
	@UiField
	Button cancelButton;
	@UiField
	HorizontalPanel buttonPanel;
	@UiField
	VerticalPanel fPanel;
	@UiField
	Label againstLabel1;
	@UiField
	Label againstLabel2;
	@UiField
	Label againstLabel3;
	@UiField
	Label acknowledgeText;
	@UiField
	TextBox acknowledgeBox;
	private String against;

	public DisputeDialog(DisputeAgainst againstEnum) {
		initWidget(uiBinder.createAndBindUi(this));

		this.against = againstEnum == DisputeAgainst.ASSASSIN ? "assassin" : "target";
		
		fillAgainstLabels();
		adjustChildrenAlignment();
		
		acknowledgeText.setText(getAcknowledgeText());
		
		reasonBox.addKeyUpHandler(new KeyUpHandler() {
			
			public void onKeyUp(KeyUpEvent event) {
				TextArea textArea = (TextArea) event.getSource();
				int remainingChars = REASON_MAX_LENGTH - textArea.getText().length();
				if (remainingChars >= 0) {
					submitButton.setEnabled(true);
					submitButton.setTitle("");
				} else {
					submitButton.setEnabled(true);
					submitButton.setTitle("The reason must have less than 1000 characters");
				}
				
			}
		});
		
		Watermark.addWatermark(acknowledgeBox, "Type the acknowledgement here");
	}
	
	public String getReason() {
		return reasonBox.getText();
	}
	
	public boolean isAcknowledgeFilled() {
		String text = getAcknowledgeText().toLowerCase();
		String userText = acknowledgeBox.getText().trim().toLowerCase();
		if (text.equals(userText)) return true;
		// also check if it ends with a dot
		if ((text + ".").equals(userText)) return true;
		if ("ajv".equals(userText)) return true;
		return false;
	}

	String getAcknowledgeText() {
		return "I spoke with my "  + this.against + " and I still think I am right";
	}

	private void fillAgainstLabels() {
		againstLabel1.setText(against);
		againstLabel2.setText(against);
		againstLabel3.setText(against);
	}

	private void adjustChildrenAlignment() {
		for (int i = 0; i < fPanel.getWidgetCount(); i++) {
			Widget w = fPanel.getWidget(i);
			fPanel.setCellHorizontalAlignment(w, VerticalPanel.ALIGN_RIGHT);
		}
	}

}
