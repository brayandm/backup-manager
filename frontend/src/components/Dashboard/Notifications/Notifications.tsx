"use client";

import { Box, Checkbox, FormControlLabel, TextField } from "@mui/material";
import React, { useState } from "react";

interface NotificationsProps {}

function Notifications({}: NotificationsProps) {
  const [isTelegramNotificationsEnabled, setIsTelegramNotificationsEnabled] =
    useState(false);
  const [apiKey, setApiKey] = useState("");
  const [channelId, setChannelId] = useState("");

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "space-between",
        width: "84vw",
        marginTop: "100px",
      }}
    >
      <Box
        sx={{
          display: "flex",
          flexDirection: "column",
          width: "300px",
          margin: "0 auto",
        }}
      >
        <FormControlLabel
          control={
            <Checkbox
              checked={isTelegramNotificationsEnabled}
              onChange={(e) =>
                setIsTelegramNotificationsEnabled(e.target.checked)
              }
              name="telegramNotifications"
            />
          }
          label="Telegram Notifications Enabled"
        />
        <TextField
          label="API Key"
          variant="outlined"
          value={apiKey}
          required
          disabled={!isTelegramNotificationsEnabled}
          onChange={(e) => setApiKey(e.target.value)}
          margin="normal"
        />
        <TextField
          label="Channel ID"
          variant="outlined"
          value={channelId}
          required
          disabled={!isTelegramNotificationsEnabled}
          onChange={(e) => setChannelId(e.target.value)}
          margin="normal"
        />
      </Box>
    </div>
  );
}

export default Notifications;
