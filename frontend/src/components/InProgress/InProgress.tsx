"use client";

import { Alert, Box, LinearProgress, Modal, Typography } from "@mui/material";
import CheckCircleOutlineIcon from "@mui/icons-material/CheckCircleOutline";

interface InProgressProps {
  title: string;
  error?: boolean;
  success?: boolean;
}

const style = {
  position: "absolute" as "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 400,
  bgcolor: "background.paper",
  p: 4,
};

function InProgress({
  title,
  error = false,
  success = false,
}: InProgressProps) {
  return (
    <>
      <Modal
        open={true}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box sx={style}>
          <Typography
            variant="h2"
            sx={{
              fontSize: "20px",
              textAlign: "center",
              marginBottom: "20px",
            }}
          >
            {title}
          </Typography>
          {success ? (
            <Alert severity="success"> Success </Alert>
          ) : error ? (
            <Alert severity="error"> There was an error </Alert>
          ) : (
            <LinearProgress />
          )}
        </Box>
      </Modal>
    </>
  );
}

export default InProgress;
