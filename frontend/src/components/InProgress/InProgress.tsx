"use client";

import { Box, LinearProgress, Modal, Typography } from "@mui/material";

interface InProgressProps {
  title: string;
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

function InProgress({ title }: InProgressProps) {
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
          <LinearProgress />
        </Box>
      </Modal>
    </>
  );
}

export default InProgress;
