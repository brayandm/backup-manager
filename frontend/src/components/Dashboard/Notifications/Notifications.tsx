"use client";

import { put } from "@/lib/backendApi";
import {
  Alert,
  Box,
  Button,
  Checkbox,
  FormControlLabel,
  TextField,
} from "@mui/material";
import React, { FormEvent, useState } from "react";

interface NotificationsProps {}

function Notifications({}: NotificationsProps) {
  const [isTelegramNotificationsEnabled, setIsTelegramNotificationsEnabled] =
    useState("false");
  const [apiKey, setApiKey] = useState("");
  const [channelId, setChannelId] = useState("");
  const [timer1Id, setTimer1Id] = useState<NodeJS.Timeout | undefined>(
    undefined
  );
  const [updateNotificationError, setUpdateNotificationError] = useState(false);
  const [updateNotificationSuccess, setUpdateNotificationSuccess] =
    useState(false);

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault();

    try {
      const res = await put("/telegram/update-settings", {
        telegram_bot_active: isTelegramNotificationsEnabled,
        telegram_bot_api_key: apiKey,
        telegram_channel_id: channelId,
      });

      if (res) {
        if (res.status !== 200) {
          clearTimeout(timer1Id);
          setUpdateNotificationError(true);
          setTimer1Id(
            setTimeout(() => setUpdateNotificationError(false), 2000)
          );
          return;
        }
        clearTimeout(timer1Id);
        setUpdateNotificationSuccess(true);
        setTimer1Id(
          setTimeout(() => setUpdateNotificationSuccess(false), 2000)
        );
      }
    } catch (error: any) {
      console.error(error);
      clearTimeout(timer1Id);
      setUpdateNotificationError(true);
      setTimer1Id(setTimeout(() => setUpdateNotificationError(false), 2000));
      return;
    }
  };

  const missingFields =
    isTelegramNotificationsEnabled === "true" ? !apiKey || !channelId : false;

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
        component="form"
        sx={{
          display: "flex",
          flexDirection: "column",
          width: "300px",
          margin: "0 auto",
        }}
        onSubmit={handleSubmit}
      >
        <FormControlLabel
          control={
            <Checkbox
              checked={isTelegramNotificationsEnabled === "true"}
              onChange={(e) =>
                setIsTelegramNotificationsEnabled(
                  e.target.checked ? "true" : "false"
                )
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
        <Button
          variant="contained"
          type="submit"
          sx={{ marginTop: "16px", marginBottom: "16px" }}
          disabled={missingFields}
        >
          Update
        </Button>
        {updateNotificationError && (
          <Alert severity="error">Update failed</Alert>
        )}
        {updateNotificationSuccess && (
          <Alert severity="success">Profile updated</Alert>
        )}
        {!updateNotificationError && !updateNotificationSuccess && (
          <Alert severity="info" sx={{ visibility: "hidden" }}></Alert>
        )}
      </Box>
    </div>
  );
}

export default Notifications;
